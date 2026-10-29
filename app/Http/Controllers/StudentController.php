<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\CommentLike;
use App\Models\Course;
use App\Models\CourseBookmark;
use App\Models\Lesson;
use App\Models\LessonComment;
use App\Models\LessonCompletion;
use App\Models\LessonNote;
use App\Models\QuizAttempt;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Get the active student user (authenticated or demonstration default).
     */
    protected function getActiveStudent(): User
    {
        return auth()->user()
            ?? User::where('role', 'student')->first()
            ?? User::first()
            ?? User::create([
                'name' => 'Siswa Pengunjung',
                'email' => 'guest@siagabumi.id',
                'password' => bcrypt('password'),
                'role' => 'student',
                'school_name' => 'SMA Negeri 1 Manado',
            ]);
    }

    /**
     * Display student catalog / dashboard (Layar 1).
     */
    public function dashboard(Request $request): View
    {
        $student = $this->getActiveStudent();

        // 1. Featured / Continue Watching Course
        $featuredQuery = Course::with(['category', 'lessons'])->where('status', 'published');
        if ($student->isGuestRole()) {
            $featuredQuery->where('is_visible_to_guest', true);
        }
        $featuredCourse = $featuredQuery->first();
        $featuredProgress = $featuredCourse ? $featuredCourse->progressPercentageFor($student) : 0;
        $featuredCompletedCount = $featuredCourse ? $featuredCourse->completedLessonsCountFor($student) : 0;
        $featuredTotalCount = $featuredCourse ? $featuredCourse->lessons->count() : 0;

        // Current active / next uncompleted lesson in featured course
        $continueLesson = null;
        if ($featuredCourse) {
            $completedIds = $student->lessonCompletions()->pluck('lesson_id')->toArray();
            $continueLesson = $featuredCourse->lessons->first(fn ($l) => ! in_array($l->id, $completedIds))
                ?? $featuredCourse->lessons->first();
        }

        // 2. Filter & Search Courses
        $query = Course::with(['category', 'lessons', 'creator'])->where('status', 'published');

        // Gallery Mode for Penyuluh / Guest: strictly view curated courses only
        if ($student->isGuestRole()) {
            $query->where('is_visible_to_guest', true);
        }

        // Multi-school scope filter: "Kursus Sekolah Saya" vs "Semua Kursus Umum"
        $scope = $request->query('scope', 'all');
        if ($scope === 'my_school' && $student->school_name) {
            $query->where('school_name', $student->school_name);
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $categorySlug = $request->query('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $allFetchedCourses = $query->latest()->get();
        try {
            $cachedCategories = Cache::remember('active_categories', 900, function () {
                return Category::withCount('courses')
                    ->orderBy('name')
                    ->get()
                    ->map(fn ($cat) => [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                        'courses_count' => (int) $cat->courses_count,
                    ])
                    ->all();
            });
        } catch (\Throwable $e) {
            $cachedCategories = Category::withCount('courses')
                ->orderBy('name')
                ->get()
                ->map(fn ($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'courses_count' => (int) $cat->courses_count,
                ])
                ->all();
        }

        $categories = collect($cachedCategories)->map(function ($cat) {
            if (is_object($cat)) {
                return (object) [
                    'id' => $cat->id ?? null,
                    'name' => $cat->name ?? '',
                    'slug' => $cat->slug ?? '',
                    'courses_count' => (int) ($cat->courses_count ?? 0),
                ];
            }
            if (is_array($cat)) {
                return (object) [
                    'id' => $cat['id'] ?? null,
                    'name' => $cat['name'] ?? '',
                    'slug' => $cat['slug'] ?? '',
                    'courses_count' => (int) ($cat['courses_count'] ?? 0),
                ];
            }

            return (object) [
                'id' => null,
                'name' => (string) $cat,
                'slug' => Str::slug((string) $cat),
                'courses_count' => 0,
            ];
        });

        // Calculate counts for each filter tab
        $countAll = $allFetchedCourses->count();
        $countOngoing = $allFetchedCourses->filter(fn ($c) => $c->progressPercentageFor($student) > 0 && $c->progressPercentageFor($student) < 100)->count();
        $countCompleted = $allFetchedCourses->filter(fn ($c) => $c->progressPercentageFor($student) === 100)->count();
        $countSaved = $allFetchedCourses->filter(fn ($c) => $c->isBookmarkedBy($student))->count();

        $mySchoolTotalCount = $student->school_name
            ? Course::where('status', 'published')->where('school_name', $student->school_name)->count()
            : 0;

        $filter = $request->query('filter', 'all');
        $filteredCourses = match ($filter) {
            'ongoing' => $allFetchedCourses->filter(fn ($c) => $c->progressPercentageFor($student) > 0 && $c->progressPercentageFor($student) < 100),
            'completed' => $allFetchedCourses->filter(fn ($c) => $c->progressPercentageFor($student) === 100),
            'saved' => $allFetchedCourses->filter(fn ($c) => $c->isBookmarkedBy($student)),
            default => $allFetchedCourses,
        };

        try {
            $stats = Cache::remember('global_catalog_stats', 900, function () {
                return [
                    'total_courses' => Course::where('status', 'published')->count(),
                    'total_lessons' => Lesson::count(),
                    'total_schools' => School::where('is_active', true)->count(),
                    'total_students' => User::where('role', 'student')->count(),
                ];
            });
        } catch (\Throwable $e) {
            $stats = [
                'total_courses' => Course::where('status', 'published')->count(),
                'total_lessons' => Lesson::count(),
                'total_schools' => School::where('is_active', true)->count(),
                'total_students' => User::where('role', 'student')->count(),
            ];
        }

        return view('student.dashboard', [
            'student' => $student,
            'featuredCourse' => $featuredCourse,
            'featuredProgress' => $featuredProgress,
            'featuredCompletedCount' => $featuredCompletedCount,
            'featuredTotalCount' => $featuredTotalCount,
            'continueLesson' => $continueLesson,
            'courses' => $filteredCourses,
            'categories' => $categories,
            'currentFilter' => $filter,
            'scope' => $scope,
            'mySchoolTotalCount' => $mySchoolTotalCount,
            'countAll' => $countAll,
            'countOngoing' => $countOngoing,
            'countCompleted' => $countCompleted,
            'countSaved' => $countSaved,
            'stats' => $stats,
        ]);
    }

    /**
     * Display classroom video player with playlist sidebar (Layar 2).
     */
    public function classroom(Course $course, ?Lesson $lesson = null): View
    {
        $student = $this->getActiveStudent();

        // Gallery Mode restriction for Penyuluh / Guest
        if ($student->isGuestRole() && ! $course->is_visible_to_guest) {
            abort(403, 'Akses Ditolak: Materi pembelajaran ini belum dibuka oleh Administrator Pusat untuk akun Penyuluh / Tamu (Mode Galeri).');
        }

        // Load course with lessons
        $course->load(['category', 'lessons']);
        $allLessons = $course->lessons;

        if ($allLessons->isEmpty()) {
            return view('student.classroom-empty', [
                'student' => $student,
                'course' => $course,
                'isBookmarked' => $course->isBookmarkedBy($student),
            ]);
        }

        // Determine active lesson
        if (! $lesson || $lesson->course_id !== $course->id) {
            // Find first uncompleted lesson, or default to first lesson
            $completedIds = $student->lessonCompletions()->pluck('lesson_id')->toArray();
            $lesson = $allLessons->first(fn ($l) => ! in_array($l->id, $completedIds)) ?? $allLessons->first();
        }

        // Load lesson relations: notes, threaded comments with replies & likes, quizzes, resources
        $lesson->load([
            'notes' => fn ($q) => $q->where('user_id', $student->id)->orderBy('timestamp_seconds'),
            'comments' => fn ($q) => $q->whereNull('parent_id')->with(['replies.likes', 'likes'])->latest(),
            'quizzes',
            'resources',
            'quizAttempts' => fn ($q) => $q->where('user_id', $student->id)->latest(),
        ]);

        // Group lessons by module_title for accordion
        $modules = $allLessons->groupBy(function ($item) {
            return $item->module_title ?: 'Modul Materi Kursus';
        });

        // Compute current course progress for student
        $completedLessonsCount = $course->completedLessonsCountFor($student);
        $totalLessonsCount = $allLessons->count();
        $progressPercentage = $course->progressPercentageFor($student);

        // Previous & Next Lesson Navigation
        $currentIndex = $allLessons->search(fn ($l) => $l->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $allLessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex !== false && $currentIndex < ($allLessons->count() - 1) ? $allLessons->get($currentIndex + 1) : null;

        $isCompleted = $lesson->isCompletedBy($student);
        $isBookmarked = $course->isBookmarkedBy($student);
        $lastWatchedSecond = $lesson->lastWatchedSecondFor($student);
        $latestQuizAttempt = $lesson->quizAttemptFor($student);
        $certificate = $course->getCertificateFor($student);
        if (! $certificate && $course->isEligibleForCertificate($student)) {
            $certificate = Certificate::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    'certificate_code' => 'CERT-'.strtoupper(Str::random(10)),
                    'issued_at' => now(),
                ]
            );
        }

        return view('student.classroom', [
            'student' => $student,
            'course' => $course,
            'currentLesson' => $lesson,
            'modules' => $modules,
            'allLessons' => $allLessons,
            'completedLessonsCount' => $completedLessonsCount,
            'totalLessonsCount' => $totalLessonsCount,
            'progressPercentage' => $progressPercentage,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
            'isCompleted' => $isCompleted,
            'isBookmarked' => $isBookmarked,
            'notes' => $lesson->notes,
            'comments' => $lesson->comments,
            'quizzes' => $lesson->quizzes,
            'resources' => $lesson->resources,
            'lastWatchedSecond' => $lastWatchedSecond,
            'quizAttempt' => $latestQuizAttempt,
            'certificate' => $certificate,
            'totalQuizzesCount' => $course->totalQuizzesCount(),
            'passedQuizzesCount' => $course->passedQuizzesCountFor($student),
            'hasPassedAllQuizzes' => $course->hasPassedAllQuizzes($student),
        ]);
    }

    /**
     * Store student personal timestamped note.
     */
    public function storeNote(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $student = $this->getActiveStudent();

        if ($student->isGuestRole()) {
            abort(403, 'Akun Penyuluh/Tamu berada dalam Mode Galeri (Lihat-Saja) dan tidak dapat menyimpan catatan.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'timestamp_seconds' => 'nullable|integer|min:0',
        ]);

        $note = LessonNote::create([
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'content' => $validated['content'],
            'timestamp_seconds' => $validated['timestamp_seconds'] ?? 0,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Catatan tersimpan!',
                'note' => $note,
                'formatted_timestamp' => $note->formatted_timestamp,
            ]);
        }

        return back()->with('success', 'Catatan pembelajaran berhasil disimpan!');
    }

    /**
     * Delete student note.
     */
    public function destroyNote(LessonNote $note): JsonResponse|RedirectResponse
    {
        $note->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Catatan berhasil dihapus.']);
        }

        return back()->with('success', 'Catatan berhasil dihapus.');
    }

    /**
     * Store community comment or threaded reply.
     */
    public function storeComment(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $student = $this->getActiveStudent();

        if ($student->isGuestRole()) {
            abort(403, 'Akun Penyuluh/Tamu berada dalam Mode Galeri (Lihat-Saja) dan tidak dapat mengirim komentar.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'timestamp_str' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:lesson_comments,id',
        ]);

        $comment = LessonComment::create([
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'user_name' => $student->name,
            'user_avatar' => $student->avatar_url,
            'timestamp_str' => ($validated['timestamp_str'] ?? null) ?: 'pada '.date('i:s'),
            'content' => $validated['content'],
            'likes_count' => 0,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dipublikasikan!',
                'comment' => $comment,
            ]);
        }

        return back()->with('success', 'Komentar Anda berhasil dipublikasikan!');
    }

    /**
     * Toggle like on a comment.
     */
    public function toggleCommentLike(Request $request, LessonComment $comment): JsonResponse
    {
        $student = $this->getActiveStudent();

        if ($student->isGuestRole()) {
            abort(403, 'Akun Penyuluh/Tamu berada dalam Mode Galeri (Lihat-Saja).');
        }

        $like = CommentLike::where('user_id', $student->id)
            ->where('lesson_comment_id', $comment->id)
            ->first();

        if ($like) {
            $like->delete();
            $comment->decrement('likes_count');
            $liked = false;
        } else {
            CommentLike::create([
                'user_id' => $student->id,
                'lesson_comment_id' => $comment->id,
            ]);
            $comment->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => max(0, (int) $comment->fresh()->likes_count),
        ]);
    }

    /**
     * Toggle bookmark for a course.
     */
    public function toggleBookmark(Request $request, Course $course): JsonResponse|RedirectResponse
    {
        $student = $this->getActiveStudent();

        if ($student->isGuestRole()) {
            abort(403, 'Akun Penyuluh/Tamu berada dalam Mode Galeri (Lihat-Saja).');
        }

        $bookmark = CourseBookmark::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
            $msg = 'Kursus dihapus dari simpanan.';
        } else {
            CourseBookmark::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
            ]);
            $bookmarked = true;
            $msg = 'Kursus berhasil disimpan ke penanda!';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'bookmarked' => $bookmarked,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Toggle lesson completion status for the student.
     */
    public function toggleCompletion(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $student = $this->getActiveStudent();

        if ($student->isGuestRole()) {
            abort(403, 'Akun Penyuluh/Tamu berada dalam Mode Galeri (Lihat-Saja) dan tidak dapat mengubah progres pembelajaran.');
        }

        $existing = LessonCompletion::where('user_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($existing && $existing->completed_at) {
            $existing->delete();
            $status = false;
            $message = 'Pelajaran ditandai belum selesai.';
        } else {
            $completion = $existing ?? new LessonCompletion([
                'user_id' => $student->id,
                'lesson_id' => $lesson->id,
            ]);
            $completion->completed_at = now();
            $completion->save();
            $status = true;
            $message = 'Pelajaran berhasil diselesaikan!';
        }

        $course = $lesson->course;
        $newProgress = $course->progressPercentageFor($student);

        // Auto-issue certificate if course reached 100% and user is eligible (passed required quizzes)
        $certificate = null;
        if ($newProgress === 100 && $course->isEligibleForCertificate($student)) {
            $certificate = Certificate::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    'certificate_code' => 'CERT-'.strtoupper(Str::random(10)),
                    'issued_at' => now(),
                ]
            );
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'completed' => $status,
                'message' => $message,
                'progress' => $newProgress,
                'completed_count' => $course->completedLessonsCountFor($student),
                'certificate' => $certificate ? [
                    'code' => $certificate->certificate_code,
                    'url' => route('student.certificate', $certificate->certificate_code),
                ] : null,
                'passed_quizzes_count' => $course->passedQuizzesCountFor($student),
                'total_quizzes_count' => $course->totalQuizzesCount(),
                'has_passed_all_quizzes' => $course->hasPassedAllQuizzes($student),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Save video watch progress seconds and auto-complete if requested.
     */
    public function saveProgress(Request $request, Lesson $lesson): JsonResponse
    {
        $student = $this->getActiveStudent();

        if ($student->isGuestRole()) {
            return response()->json(['success' => false, 'message' => 'Akun Penyuluh/Tamu berada dalam Mode Galeri (Lihat-Saja) dan tidak menyimpan progres.'], 403);
        }

        $validated = $request->validate([
            'seconds' => 'nullable|integer|min:0',
            'last_watched_second' => 'nullable|integer|min:0',
            'mark_completed' => 'nullable|boolean',
            'completed' => 'nullable|boolean',
        ]);

        $completion = LessonCompletion::firstOrNew([
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
        ]);

        $completion->last_watched_second = $validated['last_watched_second'] ?? $validated['seconds'] ?? 0;

        $shouldComplete = $request->boolean('mark_completed') || $request->boolean('completed');
        if ($shouldComplete && ! $completion->completed_at) {
            $completion->completed_at = now();
        }

        $completion->save();

        $course = $lesson->course;
        $newProgress = $course->progressPercentageFor($student);

        // Auto-generate certificate if course is 100% completed and user is eligible
        $certificate = null;
        if ($newProgress === 100 && $course->isEligibleForCertificate($student)) {
            $certificate = Certificate::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    'certificate_code' => 'CERT-'.strtoupper(Str::random(10)),
                    'issued_at' => now(),
                ]
            );
        }

        return response()->json([
            'success' => true,
            'last_watched_second' => $completion->last_watched_second,
            'is_completed' => (bool) $completion->completed_at,
            'progress' => $newProgress,
            'course_completed' => ($newProgress === 100),
            'completed_count' => $course->completedLessonsCountFor($student),
            'certificate' => $certificate ? [
                'code' => $certificate->certificate_code,
                'url' => route('student.certificate', $certificate->certificate_code),
            ] : null,
            'passed_quizzes_count' => $course->passedQuizzesCountFor($student),
            'total_quizzes_count' => $course->totalQuizzesCount(),
            'has_passed_all_quizzes' => $course->hasPassedAllQuizzes($student),
        ]);
    }

    /**
     * Submit answers for lesson quiz checkpoint and persist attempt.
     */
    public function submitQuiz(Request $request, Lesson $lesson): JsonResponse
    {
        $student = $this->getActiveStudent();

        if ($student->isGuestRole()) {
            abort(403, 'Akun Penyuluh/Tamu berada dalam Mode Galeri (Lihat-Saja) dan tidak dapat mengikuti evaluasi kuis.');
        }

        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $quizzes = $lesson->quizzes;
        if ($quizzes->isEmpty()) {
            return response()->json(['error' => 'Tidak ada soal kuis untuk materi ini.'], 400);
        }

        $answers = $validated['answers'];
        $correctCount = 0;
        $totalQuestions = $quizzes->count();
        $feedback = [];

        foreach ($quizzes as $quiz) {
            $selected = isset($answers[$quiz->id]) ? (int) $answers[$quiz->id] : null;
            $isCorrect = $selected !== null && $selected === $quiz->correct_answer_index;
            if ($isCorrect) {
                $correctCount++;
            }

            $feedback[$quiz->id] = [
                'question' => $quiz->question,
                'selected' => $selected,
                'correct' => $quiz->correct_answer_index,
                'is_correct' => $isCorrect,
                'explanation' => $quiz->explanation,
            ];
        }

        $firstQuestion = $quizzes->first();
        $quizModel = $firstQuestion?->quiz;
        $passingGrade = $quizModel?->passing_grade ?? 70;

        $score = (int) round(($correctCount / $totalQuestions) * 100);
        $passed = $score >= $passingGrade;

        $attempt = QuizAttempt::create([
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'quiz_id' => $quizModel?->id,
            'score' => $score,
            'passed' => $passed,
            'answers' => $answers,
        ]);

        $course = $lesson->course;
        $certificate = null;
        if ($course && $course->isEligibleForCertificate($student)) {
            $certificate = Certificate::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    'certificate_code' => 'CERT-'.strtoupper(Str::random(10)),
                    'issued_at' => now(),
                ]
            );
        }

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
            'score' => $score,
            'passed' => $passed,
            'passing_grade' => $passingGrade,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'feedback' => $feedback,
            'certificate' => $certificate ? [
                'code' => $certificate->certificate_code,
                'url' => route('student.certificate', $certificate->certificate_code),
            ] : null,
            'passed_quizzes_count' => $course ? $course->passedQuizzesCountFor($student) : 0,
            'total_quizzes_count' => $course ? $course->totalQuizzesCount() : 0,
            'has_passed_all_quizzes' => $course ? $course->hasPassedAllQuizzes($student) : true,
            'created_at' => $attempt->created_at->diffForHumans(),
        ]);
    }

    /**
     * Display printable and verifiably dynamic certificate.
     */
    public function viewCertificate(string $code): View
    {
        $certificate = Certificate::where('certificate_code', $code)
            ->with(['user.school', 'course.school', 'course.category', 'course.creator'])
            ->firstOrFail();

        $verificationUrl = route('student.certificate', $certificate->certificate_code);

        return view('student.certificate', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
            'verificationUrl' => $verificationUrl,
            'isDownloadMode' => false,
        ]);
    }

    /**
     * Display structured Learning Paths / Tracks view.
     */
    public function learningPaths(): View
    {
        $student = $this->getActiveStudent();

        $courses = Course::with(['category', 'lessons'])->get();

        $paths = [
            [
                'title' => 'Alur Rekayasa Web Full-Stack Modern',
                'slug' => 'fullstack-web',
                'description' => 'Dari perancangan frontend reaktif, Next.js 15 Server Components, hingga manajemen state optimistik dan caching enterprise.',
                'icon' => 'code',
                'badge' => 'Jalur Populer',
                'courses' => $courses->filter(fn ($c) => in_array($c->category?->slug, ['frontend', 'backend'])),
                'level' => 'Menengah ke Lanjut',
                'total_modules' => 12,
            ],
            [
                'title' => 'Alur Cloud, Kontainer & Site Reliability (SRE)',
                'slug' => 'cloud-devops',
                'description' => 'Kuasai otomatisasi container Docker multi-stage, orkestrasi pod Kubernetes, dan penyebaran pipeline CI/CD skalabel.',
                'icon' => 'cloud',
                'badge' => 'Industri Enterprise',
                'courses' => $courses->filter(fn ($c) => $c->category?->slug === 'devops'),
                'level' => 'Pemula ke Menengah',
                'total_modules' => 6,
            ],
            [
                'title' => 'Alur Algoritma & Arsitektur Terdistribusi',
                'slug' => 'algorithms-systems',
                'description' => 'Eksplorasi struktur data kompleks, graf, optimasi kompleksitas Big-O, caching Redis, dan load balancer multi-region.',
                'icon' => 'hub',
                'badge' => 'Fondasi Inti',
                'courses' => $courses->filter(fn ($c) => in_array($c->category?->slug, ['algoritma', 'backend'])),
                'level' => 'Tingkat Lanjut',
                'total_modules' => 10,
            ],
        ];

        return view('student.paths', [
            'student' => $student,
            'paths' => $paths,
        ]);
    }

    /**
     * Display Student Learning Analytics & Telemetry.
     */
    public function analytics(): View
    {
        $student = $this->getActiveStudent();

        $completedLessonIds = $student->lessonCompletions()->whereNotNull('completed_at')->pluck('lesson_id');
        $completedLessons = Lesson::whereIn('id', $completedLessonIds)->with('course.category')->get();

        $totalCompletedLessons = $completedLessons->count();
        $totalSecondsWatched = (int) $completedLessons->sum('duration_seconds');
        $totalHoursWatched = round($totalSecondsWatched / 3600, 1);

        $courses = Course::with(['category', 'lessons'])->get();
        $completedCoursesCount = $courses->filter(fn ($c) => $c->progressPercentageFor($student) === 100)->count();
        $inProgressCoursesCount = $courses->filter(fn ($c) => $c->progressPercentageFor($student) > 0 && $c->progressPercentageFor($student) < 100)->count();
        $bookmarkedCoursesCount = CourseBookmark::where('user_id', $student->id)->count();

        $notesCount = LessonNote::where('user_id', $student->id)->count();
        $commentsCount = LessonComment::where('user_id', $student->id)->count();

        // Dynamic Quiz Statistics
        $quizAttemptsCount = QuizAttempt::where('user_id', $student->id)->count();
        $averageQuizScore = round((float) (QuizAttempt::where('user_id', $student->id)->avg('score') ?? 0), 1);
        $certificates = Certificate::where('user_id', $student->id)->with('course')->latest()->get();

        $categories = Category::with('courses.lessons')->get()->map(function ($cat) use ($student) {
            $catCourses = $cat->courses;
            $totalCatLessons = $catCourses->flatMap->lessons->count();
            $completedCatLessons = $catCourses->sum(fn ($c) => $c->completedLessonsCountFor($student));
            $pct = $totalCatLessons > 0 ? (int) round(($completedCatLessons / $totalCatLessons) * 100) : 0;

            return [
                'name' => $cat->name,
                'total_lessons' => $totalCatLessons,
                'completed_lessons' => $completedCatLessons,
                'percentage' => $pct,
            ];
        });

        $recentCompletions = $student->lessonCompletions()
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(6)
            ->get();

        return view('student.analytics', [
            'student' => $student,
            'totalCompletedLessons' => $totalCompletedLessons,
            'totalHoursWatched' => $totalHoursWatched,
            'completedCoursesCount' => $completedCoursesCount,
            'inProgressCoursesCount' => $inProgressCoursesCount,
            'bookmarkedCoursesCount' => $bookmarkedCoursesCount,
            'notesCount' => $notesCount,
            'commentsCount' => $commentsCount,
            'quizAttemptsCount' => $quizAttemptsCount,
            'averageQuizScore' => $averageQuizScore,
            'certificates' => $certificates,
            'categoryStats' => $categories,
            'recentCompletions' => $recentCompletions,
        ]);
    }

    /**
     * Download certificate in dedicated clean print/export view.
     */
    public function downloadCertificate(string $code): View
    {
        $certificate = Certificate::where('certificate_code', $code)
            ->with(['user.school', 'course.school', 'course.category'])
            ->firstOrFail();

        $verificationUrl = route('student.certificate', $certificate->certificate_code);

        return view('student.certificate', [
            'certificate' => $certificate,
            'verificationUrl' => $verificationUrl,
            'isDownloadMode' => true,
        ]);
    }
}
