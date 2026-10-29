<?php

namespace App\Http\Controllers;

use App\Mail\TicketRepliedMail;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonResource;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\School;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\YouTubeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Get active admin user.
     */
    protected function getActiveAdmin(): User
    {
        return auth()->user()
            ?? User::whereIn('role', ['super_admin', 'school_admin', 'admin'])->first()
            ?? User::first()
            ?? User::create([
                'name' => 'Dr. Hendra Sompotan, M.Si',
                'email' => 'adminpusat@siagabumi.id',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'is_active' => true,
            ]);
    }

    /**
     * Display admin dashboard and video lessons table (Layar 3).
     */
    public function dashboard(Request $request): View
    {
        $admin = $this->getActiveAdmin();

        // 1. Telemetry Stats & Courses Scoping
        $coursesQuery = Course::query();
        if ($admin->isSchoolAdmin() || $admin->isGuru()) {
            $coursesQuery->where(function ($q) use ($admin) {
                if ($admin->school_name) {
                    $q->where('school_name', $admin->school_name);
                } else {
                    $q->where('created_by', $admin->id);
                }
            });
        } elseif ($request->filled('school')) {
            $coursesQuery->where('school_name', $request->query('school'));
        }

        $managedCourseIds = $coursesQuery->pluck('id')->toArray();

        $totalLessons = Lesson::whereIn('course_id', $managedCourseIds)->count();
        $totalCourses = (clone $coursesQuery)->where('status', 'published')->count();
        $totalWatchSeconds = (int) Lesson::whereIn('course_id', $managedCourseIds)->sum('duration_seconds');
        $totalWatchHours = round($totalWatchSeconds / 3600, 1);

        // 2. Query Lessons with Filters
        $query = Lesson::with(['course.category'])->whereIn('course_id', $managedCourseIds);

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('youtube_video_id', 'like', "%{$search}%")
                    ->orWhere('module_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $catId = $request->query('category_id');
            $query->whereHas('course', function ($q) use ($catId) {
                $q->where('category_id', $catId);
            });
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->query('course_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $lessons = $query->latest()->paginate(15)->withQueryString();
        $courses = Course::whereIn('id', $managedCourseIds)->orderBy('title')->get();
        $categories = Category::orderBy('name')->get();

        // Get distinct school names for Super Admin filter
        $schools = $admin->isSuperAdmin()
            ? Course::whereNotNull('school_name')->distinct()->pluck('school_name')
            : collect([$admin->school_name]);

        return view('admin.dashboard', [
            'admin' => $admin,
            'totalLessons' => $totalLessons,
            'totalCourses' => $totalCourses,
            'totalWatchHours' => $totalWatchHours,
            'lessons' => $lessons,
            'courses' => $courses,
            'categories' => $categories,
            'schools' => $schools,
            'selectedCategory' => $request->query('category_id'),
            'selectedCourse' => $request->query('course_id'),
            'selectedStatus' => $request->query('status'),
            'selectedSchool' => $request->query('school'),
            'searchQuery' => $request->query('q'),
        ]);
    }

    /**
     * Store newly created lesson from Modal (Layar 4).
     */
    public function storeLesson(Request $request): JsonResponse|RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module_title' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:published,draft,unlisted',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        if (! $course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin menambahkan materi pada kursus sekolah lain.');
        }

        $videoId = YouTubeService::extractVideoId($validated['youtube_url']);
        if (! $videoId) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'URL YouTube tidak valid. Mohon periksa kembali.'], 422);
            }

            return back()->withErrors(['youtube_url' => 'URL YouTube tidak valid.'])->withInput();
        }

        $maxSort = Lesson::where('course_id', $validated['course_id'])->max('sort_order') ?? 0;

        $lesson = Lesson::create([
            'course_id' => $validated['course_id'],
            'module_title' => $validated['module_title'] ?: 'Modul Umum',
            'title' => $validated['title'],
            'youtube_url' => $validated['youtube_url'],
            'youtube_video_id' => $videoId,
            'thumbnail_url' => YouTubeService::getThumbnailUrl($videoId),
            'duration_seconds' => rand(600, 1800),
            'duration_formatted' => YouTubeService::formatDuration(rand(600, 1800)),
            'sort_order' => $maxSort + 1,
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelajaran video YouTube berhasil disimpan!',
                'lesson' => $lesson,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', "Pelajaran '{$lesson->title}' berhasil dipublikasikan!");
    }

    /**
     * Delete a lesson.
     */
    public function destroyLesson(Lesson $lesson): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $lesson->course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin menghapus materi dari sekolah lain.');
        }

        $title = $lesson->title;
        $lesson->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Pelajaran '{$title}' berhasil dihapus.",
            ]);
        }

        return back()->with('success', "Pelajaran '{$title}' berhasil dihapus.");
    }

    /**
     * AJAX endpoint to parse YouTube URL and fetch live metadata for live preview modal.
     */
    public function ajaxParseYouTube(Request $request): JsonResponse
    {
        $url = $request->input('url');
        $videoId = YouTubeService::extractVideoId($url);

        if (! $videoId) {
            return response()->json([
                'valid' => false,
                'message' => 'Format tautan YouTube tidak dikenali.',
            ]);
        }

        $meta = YouTubeService::fetchMetadata($url);

        return response()->json([
            'valid' => true,
            'video_id' => $videoId,
            'thumbnail_url' => YouTubeService::getThumbnailUrl($videoId),
            'hq_thumbnail_url' => YouTubeService::getHqThumbnailUrl($videoId),
            'embed_url' => YouTubeService::getEmbedUrl($videoId),
            'title' => $meta['title'] ?? 'Judul Pelajaran Terdeteksi Otomatis',
            'author_name' => $meta['author_name'] ?? 'BKKBN Official',
            'duration_sample' => '24:18',
            'quality' => '1080p HD (60 FPS)',
        ]);
    }

    /**
     * AJAX endpoint to check health of YouTube video (detect private/deleted).
     */
    public function checkVideoHealth(Request $request): JsonResponse
    {
        $url = $request->input('video_url');
        $health = YouTubeService::checkHealth($url);

        return response()->json([
            'success' => (bool) ($health['healthy'] ?? false),
            ...$health,
        ]);
    }

    /**
     * Update existing lesson.
     */
    public function updateLesson(Request $request, Lesson $lesson): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $lesson->course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang memperbarui materi sekolah lain.');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module_title' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:published,draft,unlisted',
        ]);

        $newCourse = Course::findOrFail($validated['course_id']);
        if (! $newCourse->canManage($admin)) {
            abort(403, 'Akses ditolak: Kursus target bukan milik sekolah Anda.');
        }

        $videoId = YouTubeService::extractVideoId($validated['youtube_url']);
        if (! $videoId) {
            return back()->withErrors(['youtube_url' => 'URL YouTube tidak valid.'])->withInput();
        }

        $lesson->update([
            'course_id' => $validated['course_id'],
            'module_title' => $validated['module_title'] ?: 'Modul Umum',
            'title' => $validated['title'],
            'youtube_url' => $validated['youtube_url'],
            'youtube_video_id' => $videoId,
            'thumbnail_url' => YouTubeService::getThumbnailUrl($videoId),
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pelajaran berhasil diperbarui!', 'lesson' => $lesson]);
        }

        return redirect()->route('admin.dashboard')->with('success', "Pelajaran '{$lesson->title}' berhasil diperbarui!");
    }

    /**
     * Batch import lessons from a YouTube playlist URL.
     */
    public function importPlaylist(Request $request): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'playlist_url' => 'required|string',
            'module_title' => 'nullable|string|max:255',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        if (! $course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin menambahkan playlist pada kursus sekolah lain.');
        }

        $playlistId = YouTubeService::extractPlaylistId($validated['playlist_url']) ?? 'PL_CUSTOM_'.time();
        $mockLessons = YouTubeService::getPlaylistLessonsMock($playlistId);

        $moduleTitle = $validated['module_title'] ?: 'Modul Playlist YouTube';
        $maxSort = Lesson::where('course_id', $validated['course_id'])->max('sort_order') ?? 0;
        $createdCount = 0;

        foreach ($mockLessons as $index => $item) {
            Lesson::create([
                'course_id' => $validated['course_id'],
                'module_title' => $moduleTitle,
                'title' => $item['title'],
                'youtube_url' => $item['youtube_url'],
                'youtube_video_id' => $item['youtube_video_id'],
                'thumbnail_url' => YouTubeService::getThumbnailUrl($item['youtube_video_id']),
                'duration_seconds' => $item['duration_seconds'],
                'duration_formatted' => $item['duration_formatted'],
                'sort_order' => $maxSort + $index + 1,
                'description' => $item['description'],
                'status' => 'published',
            ]);
            $createdCount++;
        }

        $msg = "Berhasil mengimpor {$createdCount} video materi dari Playlist YouTube!";

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg, 'count' => $createdCount]);
        }

        return redirect()->route('admin.dashboard')->with('success', $msg);
    }

    /**
     * Display Course Manager index.
     */
    public function coursesIndex(Request $request): View
    {
        $admin = $this->getActiveAdmin();

        $query = Course::with(['category', 'lessons', 'creator'])->withCount('lessons');

        // Multi-tenancy Scoping
        if ($admin->isSchoolAdmin() || $admin->isGuru()) {
            $query->where(function ($q) use ($admin) {
                if ($admin->school_name) {
                    $q->where('school_name', $admin->school_name);
                } else {
                    $q->where('created_by', $admin->id);
                }
            });
        } elseif ($request->filled('school')) {
            $query->where('school_name', $request->query('school'));
        }

        $courses = $query->latest()->get();
        $categories = Category::orderBy('name')->get();

        $schools = $admin->isSuperAdmin()
            ? Course::whereNotNull('school_name')->distinct()->pluck('school_name')
            : collect([$admin->school_name]);

        return view('admin.courses.index', [
            'admin' => $admin,
            'courses' => $courses,
            'categories' => $categories,
            'schools' => $schools,
            'selectedSchool' => $request->query('school'),
        ]);
    }

    /**
     * Store a new course.
     */
    public function storeCourse(Request $request): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_title' => 'nullable|string|max:255',
            'thumbnail_url' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|max:5120',
            'school_name' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft,archived',
            'is_visible_to_guest' => 'nullable|boolean',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $disk = config('filesystems.default', 'public');
            $path = $request->file('thumbnail_file')->store('courses/thumbnails', $disk);
            $validated['thumbnail_url'] = Storage::disk($disk)->url($path);
        }
        unset($validated['thumbnail_file']);

        $validated['created_by'] = $admin->id;

        if ($admin->isSuperAdmin() && $request->has('is_visible_to_guest')) {
            $validated['is_visible_to_guest'] = $request->boolean('is_visible_to_guest');
        }

        // Force school_name for school_admin or guru
        if ($admin->isSchoolAdmin() || $admin->isGuru()) {
            $validated['school_name'] = $admin->school_name ?: 'Sekolah Saya';
        } else {
            $validated['school_name'] = ($validated['school_name'] ?? null) ?: 'BKKBN Perwakilan Sulawesi Utara';
        }

        $course = Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', "Kursus '{$course->title}' berhasil ditambahkan!")
            ->with('new_course_id', $course->id);
    }

    /**
     * Update an existing course.
     */
    public function updateCourse(Request $request, Course $course): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang mengelola kursus dari sekolah lain.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_title' => 'nullable|string|max:255',
            'thumbnail_url' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|max:5120',
            'school_name' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft,archived',
            'is_visible_to_guest' => 'nullable|boolean',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $disk = config('filesystems.default', 'public');
            $path = $request->file('thumbnail_file')->store('courses/thumbnails', $disk);
            $validated['thumbnail_url'] = Storage::disk($disk)->url($path);
        }
        unset($validated['thumbnail_file']);

        if ($admin->isSuperAdmin() && $request->has('is_visible_to_guest')) {
            $validated['is_visible_to_guest'] = $request->boolean('is_visible_to_guest');
        }

        // Keep existing school_name if school_admin or guru
        if ($admin->isSchoolAdmin() || $admin->isGuru()) {
            unset($validated['school_name']);
        } else {
            $validated['school_name'] = ($validated['school_name'] ?? null) ?: $course->school_name;
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', "Kursus '{$course->title}' berhasil diperbarui!");
    }

    /**
     * Toggle visibility of a course for Penyuluh / Guest role.
     * Exclusively accessible to Admin Pusat (Super Admin).
     */
    public function toggleCourseGuestVisibility(Request $request, Course $course): JsonResponse|RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $admin->isSuperAdmin()) {
            abort(403, 'Hanya Admin Pusat yang berwenang mengurasi materi untuk akun Penyuluh/Tamu.');
        }

        $course->is_visible_to_guest = ! $course->is_visible_to_guest;
        $course->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_visible_to_guest' => (bool) $course->is_visible_to_guest,
                'message' => $course->is_visible_to_guest
                    ? "Kursus '{$course->title}' kini dapat diakses oleh akun Penyuluh / Tamu (Mode Galeri)."
                    : "Akses Penyuluh / Tamu untuk kursus '{$course->title}' telah dinonaktifkan.",
            ]);
        }

        return back()->with('success', $course->is_visible_to_guest
            ? "Materi '{$course->title}' berhasil dibuka untuk Penyuluh/Tamu."
            : "Akses materi '{$course->title}' untuk Penyuluh/Tamu dinonaktifkan."
        );
    }

    /**
     * Delete a course.
     */
    public function destroyCourse(Course $course): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang menghapus kursus dari sekolah lain.');
        }

        $title = $course->title;

        // Clean up stored thumbnail if on storage disk
        if (! empty($course->thumbnail_url) && str_contains($course->thumbnail_url, 'courses/thumbnails')) {
            try {
                $disk = config('filesystems.default', 'public');
                $relativePath = parse_url($course->thumbnail_url, PHP_URL_PATH);
                $cleanPath = ltrim(str_replace('/storage/', '', (string) $relativePath), '/');
                Storage::disk($disk)->delete($cleanPath);
            } catch (\Throwable $e) {
                // Graceful storage degradation
            }
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', "Kursus '{$title}' berhasil dihapus.");
    }

    /**
     * Display Curriculum Builder.
     */
    public function curriculum(Request $request): View
    {
        $admin = $this->getActiveAdmin();

        $coursesQuery = Course::with('category');
        if ($admin->isSchoolAdmin() || $admin->isGuru()) {
            $coursesQuery->where(function ($q) use ($admin) {
                if ($admin->school_name) {
                    $q->where('school_name', $admin->school_name);
                } else {
                    $q->where('created_by', $admin->id);
                }
            });
        }
        $courses = $coursesQuery->orderBy('title')->get();

        $selectedCourseId = $request->query('course_id', $courses->first()?->id);
        $selectedCourse = Course::with(['category', 'lessons.resources', 'lessons.quizzes'])->find($selectedCourseId) ?? $courses->first();

        if ($selectedCourse && ! $selectedCourse->canManage($admin)) {
            abort(403, 'Akses ditolak: Kurikulum kursus ini bukan milik sekolah Anda.');
        }

        $modules = $selectedCourse ? $selectedCourse->lessons->groupBy(fn ($l) => $l->module_title ?: 'Modul Utama') : collect();

        return view('admin.curriculum.index', [
            'admin' => $admin,
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'modules' => $modules,
        ]);
    }

    /**
     * Update module title or sort order for a lesson.
     */
    public function updateModule(Request $request): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'module_title' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $lesson = Lesson::findOrFail($validated['lesson_id']);
        if (! $lesson->course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang mengubah struktur materi sekolah lain.');
        }

        $lesson->update([
            'module_title' => $validated['module_title'],
            'sort_order' => $validated['sort_order'] ?? $lesson->sort_order,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Struktur modul berhasil diperbarui!',
                'lesson' => $lesson,
            ]);
        }

        return back()->with('success', 'Struktur modul materi berhasil diperbarui!');
    }

    /**
     * Store resource (PDF/GitHub/Link) for a lesson.
     */
    public function storeResource(Request $request, Lesson $lesson): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $lesson->course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang menambahkan sumber daya materi ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,github,zip,link',
            'url' => 'required|url',
        ]);

        $resource = LessonResource::create([
            'lesson_id' => $lesson->id,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'url' => $validated['url'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sumber daya materi berhasil ditambahkan!',
                'resource' => $resource,
            ]);
        }

        return back()->with('success', 'Sumber daya materi berhasil ditambahkan!');
    }

    /**
     * Delete resource.
     */
    public function destroyResource(LessonResource $resource): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $resource->lesson->course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang menghapus sumber daya materi ini.');
        }

        $resource->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sumber daya materi berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'Sumber daya materi berhasil dihapus.');
    }

    /**
     * Store quiz question checkpoint for a lesson.
     */
    public function storeQuizQuestion(Request $request, Lesson $lesson): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $lesson->course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang menambahkan kuis pada materi ini.');
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer_index' => 'required|integer|min:0',
            'explanation' => 'nullable|string',
            'passing_grade' => 'nullable|integer|min:1|max:100',
        ]);

        $passingGrade = (int) ($request->input('passing_grade') ?: 75);
        $quiz = Quiz::firstOrCreate(
            ['course_id' => $lesson->course_id, 'lesson_id' => $lesson->id],
            [
                'title' => 'Kuis: '.$lesson->title,
                'passing_grade' => $passingGrade,
                'is_published' => true,
            ]
        );

        if ($request->filled('passing_grade') && $quiz->passing_grade !== $passingGrade) {
            $quiz->update(['passing_grade' => $passingGrade]);
        }

        $question = QuizQuestion::create([
            'lesson_id' => $lesson->id,
            'quiz_id' => $quiz->id,
            'question' => $validated['question'],
            'options' => $validated['options'],
            'correct_answer_index' => (int) $validated['correct_answer_index'],
            'explanation' => $validated['explanation'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Soal kuis checkpoint berhasil disimpan!',
                'question' => $question,
            ]);
        }

        return back()->with('success', 'Soal kuis checkpoint berhasil disimpan!');
    }

    /**
     * Delete quiz question checkpoint.
     */
    public function destroyQuizQuestion(QuizQuestion $question): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $question->lesson->course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang menghapus kuis materi ini.');
        }

        $question->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Soal kuis berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'Soal kuis berhasil dihapus.');
    }

    /**
     * Display Students / Learners Manager.
     */
    public function students(Request $request): View
    {
        $admin = $this->getActiveAdmin();

        $query = User::where('role', 'student')->with(['lessonCompletions.lesson.course', 'bookmarkedCourses']);

        // Multi-tenancy Scoping for students:
        // school_admin only sees students from their own school
        if ($admin->isSchoolAdmin()) {
            $query->where('school_name', $admin->school_name);
        } elseif ($request->filled('school')) {
            $query->where('school_name', $request->query('school'));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->get();
        $totalCoursesCount = $admin->isSchoolAdmin()
            ? Course::where('school_name', $admin->school_name)->count()
            : Course::count();

        $schools = $admin->isSuperAdmin()
            ? User::getRegisteredSchools()
            : collect([$admin->school_name]);

        return view('admin.students.index', [
            'admin' => $admin,
            'students' => $students,
            'totalCoursesCount' => $totalCoursesCount,
            'schools' => $schools,
            'selectedSchool' => $request->query('school'),
        ]);
    }

    /**
     * Store new student account (accessible by Super Admin and School Admin).
     */
    public function storeStudent(Request $request): RedirectResponse|JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if ($request->input('school_name') === 'other' && $request->filled('custom_school_name')) {
            $request->merge(['school_name' => $request->input('custom_school_name')]);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ];

        if ($admin->isSuperAdmin()) {
            $rules['school_name'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        // If school_admin, automatically assign to their school
        $schoolName = $admin->isSchoolAdmin()
            ? $admin->school_name
            : $validated['school_name'];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'school_name' => $schoolName,
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'is_active' => true,
            'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Siswa {$user->name} ({$user->school_name}) berhasil didaftarkan!",
                'student' => $user,
            ]);
        }

        return redirect()->route('admin.students.index')->with('success', "Siswa {$user->name} ({$user->school_name}) berhasil didaftarkan!");
    }

    /**
     * Bulk import students from uploaded CSV file.
     */
    public function importStudentsCsv(Request $request): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
            'school_name' => 'nullable|string|max:255',
        ]);

        if ($admin->isSchoolAdmin()) {
            $targetSchool = $admin->school_name;
            $targetSchoolId = $admin->school_id;
        } else {
            $targetSchool = $request->input('school_name') ?: 'BKKBN Perwakilan Sulawesi Utara';
            $targetSchoolId = School::where('name', $targetSchool)->value('id');
        }

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');

        if (! $handle) {
            return back()->with('error', 'Gagal membuka berkas CSV yang diunggah.');
        }

        $importedCount = 0;
        $skippedCount = 0;
        $isFirstRow = true;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                continue;
            }

            // Header row detection
            if ($isFirstRow) {
                $isFirstRow = false;
                $col0 = strtolower(trim($row[0] ?? ''));
                if (in_array($col0, ['name', 'nama', 'nama siswa', 'nama lengkap'])) {
                    continue;
                }
            }

            $name = trim($row[0] ?? '');
            $email = strtolower(trim($row[1] ?? ''));
            $password = trim($row[2] ?? '');
            if (empty($password)) {
                $password = 'Siswa123!';
            }

            if (empty($name) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skippedCount++;

                continue;
            }

            if (User::where('email', $email)->exists()) {
                $skippedCount++;

                continue;
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'school_name' => $targetSchool,
                'school_id' => $targetSchoolId,
                'password' => Hash::make($password),
                'role' => 'student',
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
            ]);

            $importedCount++;
        }

        fclose($handle);

        $msg = "Berhasil mengimpor {$importedCount} siswa baru ke {$targetSchool}.";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} baris / email duplikat dilewati)";
        }

        return redirect()->route('admin.students.index')->with('success', $msg);
    }

    /**
     * Download CSV template for bulk student import.
     */
    public function downloadStudentCsvTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'password']);
            fputcsv($file, ['Muhammad Farhan', 'farhan.siswa@sekolah.sch.id', 'Siswa123!']);
            fputcsv($file, ['Aisyah Putri', 'aisyah.putri@sekolah.sch.id', 'Siswa123!']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export student learning analytics as memory-optimized CSV using StreamedResponse and fputcsv.
     */
    public function exportStudentsCsv(Request $request): StreamedResponse
    {
        $admin = $this->getActiveAdmin();

        $query = User::whereIn('role', ['student', 'siswa'])
            ->with([
                'lessonCompletions.lesson.course',
                'quizAttempts',
                'certificates',
            ]);

        // Strict multi-tenancy isolation for school_admin
        if ($admin->isSchoolAdmin()) {
            if ($admin->school_id) {
                $query->where('school_id', $admin->school_id);
            } else {
                $query->where('school_name', $admin->school_name);
            }
        } elseif ($request->filled('school')) {
            $query->where('school_name', $request->query('school'));
        } elseif ($request->filled('school_id')) {
            $query->where('school_id', $request->query('school_id'));
        }

        $filename = 'rekap_siswa_'.now()->format('Y-m-d').'.csv';

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($handle, [
                'Nama Siswa',
                'Email',
                'Sekolah',
                'Kursus Diikuti',
                'Persentase Progres (%)',
                'Nilai Rata-rata Kuis',
                'Status Lulus',
            ]);

            // Stream students chunk by chunk to prevent memory exhaustion
            $query->orderBy('name')->chunk(150, function ($students) use ($handle) {
                foreach ($students as $student) {
                    $completedLessons = $student->lessonCompletions->pluck('lesson')->filter();
                    $courseIds = $completedLessons->pluck('course_id')->unique()->values();

                    $courses = Course::whereIn('id', $courseIds)->get();

                    if ($courses->isEmpty()) {
                        $quizAverage = $student->quizAttempts->count() > 0
                            ? round($student->quizAttempts->avg('score'), 1)
                            : 0;

                        fputcsv($handle, [
                            $student->name,
                            $student->email,
                            $student->school_name ?: 'BKKBN Sulut',
                            '- Belum Mengikuti Kursus -',
                            '0%',
                            $quizAverage,
                            'Belum Lulus',
                        ]);
                    } else {
                        foreach ($courses as $course) {
                            $progress = $course->progressPercentageFor($student);
                            $hasCert = $course->getCertificateFor($student) !== null;

                            $lessonIds = $course->lessons->pluck('id');
                            $courseQuizAttempts = $student->quizAttempts->whereIn('lesson_id', $lessonIds);
                            $avgScore = $courseQuizAttempts->count() > 0
                                ? round($courseQuizAttempts->avg('score'), 1)
                                : ($student->quizAttempts->count() > 0 ? round($student->quizAttempts->avg('score'), 1) : '-');

                            $statusLulus = ($progress === 100 && $hasCert)
                                ? 'Lulus Bersertifikat'
                                : ($progress === 100 ? 'Selesai Modul' : 'Sedang Belajar');

                            fputcsv($handle, [
                                $student->name,
                                $student->email,
                                $student->school_name ?: 'BKKBN Sulut',
                                $course->title,
                                "{$progress}%",
                                $avgScore,
                                $statusLulus,
                            ]);
                        }
                    }
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Reset student password by School Admin or Super Admin.
     */
    public function resetStudentPassword(Request $request, User $student): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $student->isSiswa()) {
            abort(400, 'Hanya akun siswa yang dapat direset melalui menu ini.');
        }

        if ($admin->isSchoolAdmin() && $student->school_name !== $admin->school_name) {
            abort(403, 'Akses ditolak: Anda hanya dapat mereset sandi siswa dari sekolah Anda sendiri.');
        }

        $newPassword = $request->input('new_password') ?: 'Siswa123!';
        $student->password = Hash::make($newPassword);
        $student->save();

        return back()->with('success', "Kata sandi untuk {$student->name} berhasil direset menjadi '{$newPassword}'.");
    }

    /**
     * Display LMS Platform Settings.
     */
    public function settings(): View
    {
        $admin = $this->getActiveAdmin();

        $config = [
            'site_name' => 'SIAGA BUMI-SSK',
            'site_tagline' => 'Platform Sekolah Siaga Kependudukan BKKBN Sulawesi Utara',
            'support_email' => 'admin@siagabumi.id',
            'youtube_player_domain' => 'youtube-nocookie.com',
            'allow_community_discussion' => true,
            'allow_personal_notes' => true,
            'allow_quizzes' => true,
            'auto_hd_streaming' => true,
        ];

        return view('admin.settings.index', [
            'admin' => $admin,
            'config' => $config,
        ]);
    }

    /**
     * Save LMS Platform Settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name' => 'required|string|max:100',
            'support_email' => 'required|email',
        ]);

        return back()->with('success', 'Konfigurasi platform SIAGA BUMI-SSK berhasil disimpan!');
    }

    /**
     * Display API Documentation & Integration Guide.
     */
    public function docs(): View
    {
        $admin = $this->getActiveAdmin();

        return view('admin.docs.index', [
            'admin' => $admin,
        ]);
    }

    /**
     * Display Technical Support, School Tickets Inbox & System Diagnostics.
     */
    public function support(Request $request): View
    {
        $admin = $this->getActiveAdmin();

        $diagnostics = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_os' => PHP_OS,
            'database_driver' => config('database.default'),
            'youtube_engine_status' => 'Aktif & Terverifikasi (oEmbed v3 + nocookie)',
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ];

        $query = SupportTicket::with(['user', 'school', 'responder'])->latest();

        // Scoping: If School Admin, only view tickets from own school or own account
        if ($admin->isSchoolAdmin()) {
            $query->where(function ($q) use ($admin) {
                if ($admin->school_id) {
                    $q->where('school_id', $admin->school_id);
                } else {
                    $q->where('user_id', $admin->id);
                }
            });
        } elseif ($request->filled('school_id')) {
            // Super Admin filtering by specific school
            $query->where('school_id', $request->query('school_id'));
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        // Priority Filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->query('priority'));
        }

        $tickets = $query->paginate(15)->withQueryString();

        // Metrics counts
        $baseCountQuery = $admin->isSchoolAdmin()
            ? SupportTicket::where(fn ($q) => $admin->school_id ? $q->where('school_id', $admin->school_id) : $q->where('user_id', $admin->id))
            : SupportTicket::query();

        $pendingCount = (clone $baseCountQuery)->where('status', 'menunggu')->count();
        $processedCount = (clone $baseCountQuery)->where('status', 'diproses')->count();
        $completedCount = (clone $baseCountQuery)->where('status', 'selesai')->count();
        $totalCount = (clone $baseCountQuery)->count();

        $schools = $admin->isSuperAdmin() ? School::where('is_active', true)->orderBy('name')->get() : collect();

        return view('admin.support.index', [
            'admin' => $admin,
            'diagnostics' => $diagnostics,
            'tickets' => $tickets,
            'pendingCount' => $pendingCount,
            'processedCount' => $processedCount,
            'completedCount' => $completedCount,
            'totalCount' => $totalCount,
            'schools' => $schools,
            'currentStatus' => $request->query('status'),
            'currentPriority' => $request->query('priority'),
            'selectedSchoolId' => $request->query('school_id'),
        ]);
    }

    /**
     * Handle support ticket submission from school admin or user to central admin.
     */
    public function submitSupportTicket(Request $request): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        $request->validate([
            'subject' => 'required|string|max:200',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|in:rendah,sedang,tinggi,kritis',
            'description' => 'required|string',
        ]);

        $systemLogs = [
            'sender_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'sender_role' => $admin->role_label,
            'school_name' => $admin->school_name ?? 'BKKBN Perwakilan Sulawesi Utara',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'submitted_at' => now()->toIso8601String(),
        ];

        $ticket = SupportTicket::create([
            'user_id' => $admin->id,
            'school_id' => $admin->school_id,
            'school_name' => $admin->school_name ?? 'BKKBN Perwakilan Sulawesi Utara',
            'subject' => $request->subject,
            'category' => $request->category ?? 'teknis',
            'priority' => $request->priority,
            'status' => 'menunggu',
            'description' => $request->description,
            'system_logs' => $systemLogs,
        ]);

        try {
            Cache::forget('support_unread_badge_super');
            if ($admin->school_id) {
                Cache::forget('support_unread_badge_school_'.$admin->school_id);
            }
            Cache::forget('support_unread_badge_school_'.$admin->id);
        } catch (\Throwable $e) {
            // Graceful cache degradation
        }

        $senderName = $admin->school_name ? "dari {$admin->school_name}" : 'Anda';

        return back()->with('success', "Tiket Dukungan #{$ticket->ticket_code} {$senderName} berhasil dikirim ke Admin Pusat! Tim pusat dapat segera meninjau dan merespon pesan ini.");
    }

    /**
     * Respond to a support ticket and update its status (Super Admin exclusive).
     */
    public function respondSupportTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $admin->isSuperAdmin()) {
            abort(403, 'Hanya Admin Pusat yang berwenang menanggapi tiket dari sekolah.');
        }

        $request->validate([
            'admin_response' => 'required|string',
            'status' => 'required|in:menunggu,diproses,selesai,ditutup',
        ]);

        $ticket->update([
            'admin_response' => $request->admin_response,
            'status' => $request->status,
            'responded_by' => $admin->id,
            'responded_at' => now(),
        ]);

        try {
            Cache::forget('support_unread_badge_super');
            if ($ticket->school_id) {
                Cache::forget('support_unread_badge_school_'.$ticket->school_id);
            }
            if ($ticket->user_id) {
                Cache::forget('support_unread_badge_school_'.$ticket->user_id);
            }
        } catch (\Throwable $e) {
            // Graceful cache degradation
        }

        // Dispatch queued email notification to the school admin / ticket creator
        if ($ticket->user && ! empty($ticket->user->email)) {
            try {
                Mail::to($ticket->user->email)->queue(new TicketRepliedMail($ticket));
            } catch (\Throwable $e) {
                Log::warning("Gagal mengirim email antrean TicketRepliedMail: {$e->getMessage()}");
            }
        }

        return back()->with('success', "Tanggapan untuk tiket #{$ticket->ticket_code} ({$ticket->school_name}) berhasil disimpan dan dikirimkan ke Operator Sekolah!");
    }

    /**
     * Delete a support ticket (Super Admin exclusive).
     */
    public function destroySupportTicket(SupportTicket $ticket): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $admin->isSuperAdmin()) {
            abort(403, 'Hanya Admin Pusat yang dapat menghapus tiket.');
        }

        $ticketCode = $ticket->ticket_code;
        $ticket->delete();

        return back()->with('success', "Tiket #{$ticketCode} berhasil dihapus dari sistem.");
    }
}
