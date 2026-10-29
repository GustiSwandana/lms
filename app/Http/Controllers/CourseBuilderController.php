<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Services\YouTubeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseBuilderController extends Controller
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
     * Display the Unified Single-Page Course Builder.
     */
    public function show(Course $course): View
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin mengelola kursus sekolah ini.');
        }

        $course->load([
            'category',
            'school',
            'lessons.resources',
            'lessons.quizzes',
            'quizzes.questions',
        ]);

        $categories = Category::orderBy('name')->get();

        // Ensure default course evaluation quiz exists
        $quiz = $course->quizzes()->firstOrCreate(
            ['course_id' => $course->id],
            [
                'title' => 'Evaluasi Kelulusan: '.$course->title,
                'passing_grade' => 75,
                'time_limit_minutes' => 15,
                'shuffle_questions' => false,
                'is_published' => true,
            ]
        );
        $quiz->load('questions');

        return view('admin.courses.builder', [
            'admin' => $admin,
            'course' => $course,
            'categories' => $categories,
            'quiz' => $quiz,
        ]);
    }

    /**
     * Instantly create a new Draft course and redirect straight into the Unified Builder.
     */
    public function createDraft(Request $request): RedirectResponse
    {
        $admin = $this->getActiveAdmin();

        $title = $request->input('title', 'Kursus Baru (Draf)');
        $category = Category::first();

        $schoolName = ($admin->isSchoolAdmin() || $admin->isGuru())
            ? ($admin->school_name ?: 'Sekolah Saya')
            : 'Pusat SSK';

        $course = Course::create([
            'category_id' => $category?->id ?? 1,
            'school_id' => $admin->school_id,
            'school_name' => $schoolName,
            'created_by' => $admin->id,
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(5)),
            'description' => 'Deskripsi program kursus baru.',
            'instructor_name' => $admin->name,
            'instructor_title' => 'Tenaga Pendidik',
            'level' => 'Semua Tingkat',
            'status' => 'draft',
        ]);

        return redirect()->route('admin.courses.builder', $course->id)
            ->with('success', 'Wadah kursus baru berhasil diinisialisasi! Silakan lengkapi info dan kurikulum materi.');
    }

    /**
     * Auto-Save Course Information via AJAX (Tab 1).
     */
    public function updateInfo(Request $request, Course $course): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'sometimes|nullable|string',
            'instructor_name' => 'sometimes|nullable|string|max:255',
            'instructor_title' => 'sometimes|nullable|string|max:255',
            'level' => 'sometimes|nullable|string|max:50',
            'thumbnail_url' => 'sometimes|nullable|string',
            'thumbnail_file' => 'sometimes|nullable|image|max:5120',
            'is_visible_to_guest' => 'sometimes|nullable|boolean',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $disk = config('filesystems.default', 'public');
            $path = $request->file('thumbnail_file')->store('courses/thumbnails', $disk);
            $validated['thumbnail_url'] = Storage::disk($disk)->url($path);
        }
        unset($validated['thumbnail_file']);

        if ($admin->isSuperAdmin() && $request->has('is_visible_to_guest')) {
            $validated['is_visible_to_guest'] = $request->boolean('is_visible_to_guest');
        } else {
            unset($validated['is_visible_to_guest']);
        }

        if (isset($validated['title']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $course->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Info kursus berhasil disimpan otomatis.',
            'course' => $course->fresh(['category']),
        ]);
    }

    /**
     * Smart Ingestion: Add YouTube Lesson via AJAX (Tab 2).
     */
    public function storeLesson(Request $request, Course $course): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'youtube_url' => 'required|string',
            'module_title' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $videoId = YouTubeService::extractVideoId($validated['youtube_url']);
        if (! $videoId) {
            return response()->json([
                'success' => false,
                'message' => 'Tautan video YouTube tidak valid. Mohon periksa kembali link Anda.',
            ], 422);
        }

        // Auto-detect title from YouTube if not provided
        $title = $validated['title'];
        $meta = YouTubeService::fetchMetadata($validated['youtube_url']);
        if (empty($title)) {
            $title = $meta['title'] ?? 'Materi Video YouTube';
        }

        $maxSort = $course->lessons()->max('sort_order') ?? 0;
        $moduleTitle = ! empty($validated['module_title']) ? $validated['module_title'] : 'Modul 1: Pendahuluan';

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'module_title' => $moduleTitle,
            'title' => $title,
            'youtube_url' => $validated['youtube_url'],
            'youtube_video_id' => $videoId,
            'thumbnail_url' => YouTubeService::getThumbnailUrl($videoId),
            'duration_seconds' => rand(600, 1500),
            'duration_formatted' => YouTubeService::formatDuration(rand(600, 1500)),
            'sort_order' => $maxSort + 1,
            'description' => $validated['description'] ?? null,
            'status' => 'published',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Video '{$lesson->title}' berhasil ditambahkan ke kurikulum!",
            'lesson' => $lesson->fresh(['resources', 'quizzes']),
        ]);
    }

    /**
     * Update Lesson details (Tab 2).
     */
    public function updateLesson(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin) || $lesson->course_id !== $course->id) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'module_title' => 'sometimes|nullable|string|max:255',
            'sort_order' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:published,draft,unlisted',
            'description' => 'sometimes|nullable|string',
        ]);

        $lesson->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diperbarui.',
            'lesson' => $lesson,
        ]);
    }

    /**
     * Delete Lesson via AJAX (Tab 2).
     */
    public function destroyLesson(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin) || $lesson->course_id !== $course->id) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $title = $lesson->title;
        $lesson->delete();

        return response()->json([
            'success' => true,
            'message' => "Video '{$title}' telah dihapus dari kurikulum.",
        ]);
    }

    /**
     * Reorder Lessons and Modules via Drag-and-Drop AJAX (Tab 2).
     */
    public function reorderLessons(Request $request, Course $course): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.sort_order' => 'required|integer|min:1',
            'lessons.*.module_title' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $course) {
            foreach ($validated['lessons'] as $item) {
                Lesson::where('id', $item['id'])
                    ->where('course_id', $course->id)
                    ->update([
                        'sort_order' => $item['sort_order'],
                        'module_title' => $item['module_title'] ?? 'Modul Umum',
                    ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Urutan silabus kurikulum berhasil diperbarui!',
        ]);
    }

    /**
     * Save Quiz Settings (Tab 3).
     */
    public function saveQuiz(Request $request, Course $course): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'passing_grade' => 'sometimes|integer|min:10|max:100',
            'time_limit_minutes' => 'sometimes|nullable|integer|min:1|max:300',
            'shuffle_questions' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
        ]);

        $quiz = $course->quizzes()->firstOrCreate(
            ['course_id' => $course->id],
            [
                'title' => 'Evaluasi Kelulusan: '.$course->title,
                'passing_grade' => 75,
                'time_limit_minutes' => 15,
                'shuffle_questions' => false,
                'is_published' => true,
            ]
        );

        $quiz->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan evaluasi kuis berhasil disimpan!',
            'quiz' => $quiz->fresh('questions'),
        ]);
    }

    /**
     * Store Question on the Quiz via AJAX (Tab 3).
     */
    public function storeQuizQuestion(Request $request, Course $course): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'quiz_id' => 'nullable|exists:quizzes,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer_index' => 'required|integer|min:0',
            'explanation' => 'nullable|string',
        ]);

        $quiz = null;
        if (! empty($validated['quiz_id'])) {
            $quiz = Quiz::where('id', $validated['quiz_id'])->where('course_id', $course->id)->first();
        }

        if (! $quiz) {
            $quiz = $course->quizzes()->firstOrCreate(
                ['course_id' => $course->id],
                [
                    'title' => 'Evaluasi Kelulusan: '.$course->title,
                    'passing_grade' => 75,
                    'time_limit_minutes' => 15,
                    'shuffle_questions' => false,
                    'is_published' => true,
                ]
            );
        }

        $lessonId = $validated['lesson_id']
            ?? $quiz->lesson_id
            ?? $course->lessons()->first()?->id;

        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'lesson_id' => $lessonId,
            'question' => $validated['question'],
            'options' => $validated['options'],
            'correct_answer_index' => (int) $validated['correct_answer_index'],
            'explanation' => $validated['explanation'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal kuis baru berhasil disimpan!',
            'question' => $question,
        ]);
    }

    /**
     * Delete Quiz Question via AJAX (Tab 3).
     */
    public function destroyQuizQuestion(Request $request, Course $course, QuizQuestion $question): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Butir soal kuis berhasil dihapus.',
        ]);
    }

    /**
     * Strictly Validate and Publish Course (Header Sticky Action).
     */
    public function publish(Request $request, Course $course): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $errors = [];

        // 1. Title validation
        if (empty(trim($course->title)) || str_contains(strtolower($course->title), 'tanpa judul')) {
            $errors[] = 'Judul kursus wajib diisi dengan nama topik pembelajaran yang jelas.';
        }

        // 2. Category validation
        if (empty($course->category_id)) {
            $errors[] = 'Kategori kursus wajib dipilih.';
        }

        // 3. Description validation
        if (empty(trim($course->description ?? ''))) {
            $errors[] = 'Deskripsi kursus tidak boleh kosong agar siswa memahami silabus pembelajaran.';
        }

        // 4. Lessons validation: At least 1 video lesson
        $lessonsCount = $course->lessons()->count();
        if ($lessonsCount === 0) {
            $errors[] = 'Kursus wajib memiliki minimal 1 video materi pelajaran di Tab Kurikulum.';
        }

        if (! empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Kursus belum memenuhi syarat publikasi.',
                'errors' => $errors,
            ], 422);
        }

        $course->update(['status' => 'published']);

        return response()->json([
            'success' => true,
            'status' => 'published',
            'message' => "Selamat! Kursus '{$course->title}' telah berhasil dipublikasikan dan sekarang dapat dipelajari oleh siswa.",
        ]);
    }

    /**
     * Unpublish Course back to Draft.
     */
    public function unpublish(Request $request, Course $course): JsonResponse
    {
        $admin = $this->getActiveAdmin();

        if (! $course->canManage($admin)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $course->update(['status' => 'draft']);

        return response()->json([
            'success' => true,
            'status' => 'draft',
            'message' => "Kursus '{$course->title}' telah dikembalikan ke status Draf.",
        ]);
    }
}
