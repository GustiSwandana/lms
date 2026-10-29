<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseBuilderController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - EduTube LMS
|--------------------------------------------------------------------------
*/

// Authentication & Session Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:auth-login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:auth-login');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'submitForgotPassword'])->name('password.email')->middleware('throttle:auth-login');
Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('password.update')->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');

// Student / Learner Routes
Route::get('/', [StudentController::class, 'dashboard'])->name('student.dashboard');
Route::get('/katalog', [StudentController::class, 'dashboard'])->name('student.catalog');
Route::get('/alur-belajar', [StudentController::class, 'learningPaths'])->name('student.paths');
Route::get('/analitik', [StudentController::class, 'analytics'])->name('student.analytics');
Route::get('/belajar/{course:slug}', [StudentController::class, 'classroom'])->name('student.classroom');
Route::get('/belajar/{course:slug}/materi/{lesson}', [StudentController::class, 'classroom'])->name('student.classroom.lesson');
Route::post('/pelajaran/{lesson}/toggle-complete', [StudentController::class, 'toggleCompletion'])->name('student.lesson.toggle');
Route::post('/pelajaran/{lesson}/progress', [StudentController::class, 'saveProgress'])->name('student.lesson.progress');
Route::post('/pelajaran/{lesson}/quiz', [StudentController::class, 'submitQuiz'])->name('student.lesson.quiz')->middleware('throttle:quiz-submissions');
Route::post('/pelajaran/{lesson}/notes', [StudentController::class, 'storeNote'])->name('student.lesson.notes.store');
Route::delete('/notes/{note}', [StudentController::class, 'destroyNote'])->name('student.notes.destroy');
Route::post('/pelajaran/{lesson}/comments', [StudentController::class, 'storeComment'])->name('student.lesson.comments.store')->middleware('throttle:discussions');
Route::post('/komentar/{comment}/like', [StudentController::class, 'toggleCommentLike'])->name('student.comment.like');
Route::post('/kursus/{course:slug}/toggle-bookmark', [StudentController::class, 'toggleBookmark'])->name('student.course.bookmark');
Route::get('/sertifikat/{code}', [StudentController::class, 'viewCertificate'])->name('student.certificate');
Route::get('/sertifikat/{code}/download', [StudentController::class, 'downloadCertificate'])->name('student.certificate.download');

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // 1. Shared Learning & Course Area: Super Admin, School Admin, Admin Pusat, Operator Sekolah, Guru
    Route::middleware(['role:super_admin,school_admin,admin_pusat,operator_sekolah,guru'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/lessons', [AdminController::class, 'storeLesson'])->name('lessons.store');
        Route::put('/lessons/{lesson}', [AdminController::class, 'updateLesson'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [AdminController::class, 'destroyLesson'])->name('lessons.destroy');
        Route::post('/youtube/parse', [AdminController::class, 'ajaxParseYouTube'])->name('youtube.parse')->middleware('throttle:youtube-ingest');
        Route::post('/youtube/check-health', [AdminController::class, 'checkVideoHealth'])->name('youtube.check-health')->middleware('throttle:youtube-ingest');
        Route::post('/youtube/import-playlist', [AdminController::class, 'importPlaylist'])->name('youtube.import-playlist')->middleware('throttle:youtube-ingest');

        // Course Manager
        Route::get('/courses', [AdminController::class, 'coursesIndex'])->name('courses.index');
        Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
        Route::put('/courses/{course}', [AdminController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('courses.destroy');

        // Unified Single-Page Course Builder
        Route::post('/courses/builder/create', [CourseBuilderController::class, 'createDraft'])->name('courses.builder.create');
        Route::get('/courses/{course}/builder', [CourseBuilderController::class, 'show'])->name('courses.builder');
        Route::patch('/courses/{course}/builder/info', [CourseBuilderController::class, 'updateInfo'])->name('courses.builder.info');
        Route::post('/courses/{course}/builder/lessons', [CourseBuilderController::class, 'storeLesson'])->name('courses.builder.lessons.store');
        Route::patch('/courses/{course}/builder/lessons/{lesson}', [CourseBuilderController::class, 'updateLesson'])->name('courses.builder.lessons.update');
        Route::delete('/courses/{course}/builder/lessons/{lesson}', [CourseBuilderController::class, 'destroyLesson'])->name('courses.builder.lessons.destroy');
        Route::post('/courses/{course}/builder/lessons/reorder', [CourseBuilderController::class, 'reorderLessons'])->name('courses.builder.lessons.reorder');
        Route::post('/courses/{course}/builder/quiz', [CourseBuilderController::class, 'saveQuiz'])->name('courses.builder.quiz.save');
        Route::post('/courses/{course}/builder/quiz/questions', [CourseBuilderController::class, 'storeQuizQuestion'])->name('courses.builder.quiz.questions.store');
        Route::delete('/courses/{course}/builder/quiz/questions/{question}', [CourseBuilderController::class, 'destroyQuizQuestion'])->name('courses.builder.quiz.questions.destroy');
        Route::post('/courses/{course}/builder/publish', [CourseBuilderController::class, 'publish'])->name('courses.builder.publish');
        Route::post('/courses/{course}/builder/unpublish', [CourseBuilderController::class, 'unpublish'])->name('courses.builder.unpublish');

        // Course Category Manager
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Curriculum Builder
        Route::get('/curriculum', [AdminController::class, 'curriculum'])->name('curriculum.index');
        Route::post('/curriculum/update-module', [AdminController::class, 'updateModule'])->name('curriculum.update-module');
        Route::post('/curriculum/lessons/{lesson}/resources', [AdminController::class, 'storeResource'])->name('curriculum.resources.store');
        Route::delete('/curriculum/resources/{resource}', [AdminController::class, 'destroyResource'])->name('curriculum.resources.destroy');
        Route::post('/curriculum/lessons/{lesson}/quizzes', [AdminController::class, 'storeQuizQuestion'])->name('curriculum.quizzes.store');
        Route::delete('/curriculum/quizzes/{question}', [AdminController::class, 'destroyQuizQuestion'])->name('curriculum.quizzes.destroy');

        // API Documentation
        Route::get('/docs', [AdminController::class, 'docs'])->name('docs.index');

        // Dukungan & Log (School Admin/Operator/Guru can file tickets & view logs)
        Route::get('/support', [AdminController::class, 'support'])->name('support.index');
        Route::post('/support/ticket', [AdminController::class, 'submitSupportTicket'])->name('support.ticket');
    });

    // 2. Student Telemetry & Creation Area: Operator Sekolah & Central Admin (Strictly blocks Guru)
    Route::middleware(['role:super_admin,school_admin,admin_pusat,operator_sekolah'])->group(function () {
        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
        Route::get('/students/export', [AdminController::class, 'exportStudentsCsv'])->name('students.export');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::post('/students/import', [AdminController::class, 'importStudentsCsv'])->name('students.import');
        Route::get('/students/import-template', [AdminController::class, 'downloadStudentCsvTemplate'])->name('students.import-template');
        Route::post('/students/{student}/reset-password', [AdminController::class, 'resetStudentPassword'])->name('students.reset-password');
    });

    // 3. Central Admin Exclusive Area: User Management, School Settings & Guest Content Curation
    Route::middleware(['role:super_admin,admin_pusat'])->group(function () {
        // Toggle Guest Visibility for Courses
        Route::post('/courses/{course}/toggle-guest-visibility', [AdminController::class, 'toggleCourseGuestVisibility'])->name('courses.toggle-guest-visibility');

        // User & School Admin Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/school-admins', [UserController::class, 'storeSchoolAdmin'])->name('users.store-school-admin');
        Route::post('/users/students', [UserController::class, 'storeStudent'])->name('users.store-student');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Master Schools Management
        Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
        Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
        Route::put('/schools/{school}', [SchoolController::class, 'update'])->name('schools.update');
        Route::post('/schools/{school}/toggle-status', [SchoolController::class, 'toggleStatus'])->name('schools.toggle-status');
        Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])->name('schools.destroy');

        // Global Platform Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Support Tickets Central Admin Actions
        Route::post('/support/tickets/{ticket}/respond', [AdminController::class, 'respondSupportTicket'])->name('support.respond');
        Route::delete('/support/tickets/{ticket}', [AdminController::class, 'destroySupportTicket'])->name('support.destroy');
    });
});
