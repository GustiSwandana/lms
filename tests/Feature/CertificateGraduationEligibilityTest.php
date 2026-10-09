<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CertificateGraduationEligibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test 1: Certificate remains locked when 100% of videos are watched but checkpoint quiz is not yet done/passed.
     */
    public function test_certificate_is_locked_when_only_videos_are_completed_but_quiz_is_not_done(): void
    {
        $school = School::first();
        $category = Category::first();

        $course = Course::create([
            'school_id' => $school->id,
            'category_id' => $category->id,
            'school_name' => $school->name,
            'title' => 'Algoritma Pemrograman Tingkat Lanjut',
            'slug' => 'algoritma-pemrograman-tingkat-lanjut',
            'description' => 'Kursus algoritma dengan evaluasi checkpoint kuis',
            'status' => 'published',
        ]);

        $lesson1 = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Materi 1: Big O Notation',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 300,
            'sort_order' => 1,
            'status' => 'published',
        ]);

        $lesson2 = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Materi 2: Dynamic Programming',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 400,
            'sort_order' => 2,
            'status' => 'published',
        ]);

        // Checkpoint quiz question on lesson 2
        $q = QuizQuestion::create([
            'lesson_id' => $lesson2->id,
            'question' => 'Apa keuntungan utama memoisasi dalam DP?',
            'options' => ['Menghindari komputasi ulang sub-masalah', 'Menghapus memory cache'],
            'correct_answer_index' => 0,
        ]);

        $student = User::create([
            'name' => 'Aditya Pratama',
            'email' => 'aditya@edutube.org',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => $school->id,
            'school_name' => $school->name,
            'is_active' => true,
        ]);

        // Watch 100% of videos (lesson 1 and lesson 2)
        LessonCompletion::create([
            'user_id' => $student->id,
            'lesson_id' => $lesson1->id,
            'completed_at' => now(),
            'last_watched_second' => 300,
        ]);
        LessonCompletion::create([
            'user_id' => $student->id,
            'lesson_id' => $lesson2->id,
            'completed_at' => now(),
            'last_watched_second' => 400,
        ]);

        $this->assertEquals(100, $course->progressPercentageFor($student));

        // Eligibility MUST be false because checkpoint quiz is not yet passed
        $this->assertFalse($course->isEligibleForCertificate($student));
        $this->assertDatabaseMissing('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // In classroom view, certificateUrl is not provided
        $response = $this->actingAs($student)->get(route('student.classroom', $course->slug));
        $response->assertStatus(200);
        $response->assertSee('Terkunci');
        $response->assertSee('Kerjakan & Lulus Kuis', false);
    }

    /**
     * Test 2: Certificate remains locked when quiz is passed but videos are not 100% completed.
     */
    public function test_certificate_is_locked_when_quiz_is_passed_but_videos_are_incomplete(): void
    {
        $school = School::first();
        $category = Category::first();

        $course = Course::create([
            'school_id' => $school->id,
            'category_id' => $category->id,
            'school_name' => $school->name,
            'title' => 'Pengenalan Machine Learning',
            'slug' => 'pengenalan-machine-learning',
            'description' => 'Materi dasar ML',
            'status' => 'published',
        ]);

        $lesson1 = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Konsep Regresi Linier',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 500,
            'sort_order' => 1,
            'status' => 'published',
        ]);

        $lesson2 = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Konsep Klasifikasi Logistic',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 500,
            'sort_order' => 2,
            'status' => 'published',
        ]);

        $q = QuizQuestion::create([
            'lesson_id' => $lesson1->id,
            'question' => 'Apa metrik evaluasi regresi linier?',
            'options' => ['Mean Squared Error (MSE)', 'Confusion Matrix'],
            'correct_answer_index' => 0,
        ]);

        $student = User::create([
            'name' => 'Citra Lestari',
            'email' => 'citra@edutube.org',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => $school->id,
            'school_name' => $school->name,
            'is_active' => true,
        ]);

        // Student passes quiz on lesson 1
        $this->actingAs($student)->postJson(route('student.lesson.quiz', $lesson1->id), [
            'answers' => [
                $q->id => 0,
            ],
        ])->assertStatus(200)->assertJson(['passed' => true]);

        // But only completed lesson 1 (50% video progress)
        LessonCompletion::create([
            'user_id' => $student->id,
            'lesson_id' => $lesson1->id,
            'completed_at' => now(),
            'last_watched_second' => 500,
        ]);

        $this->assertEquals(50, $course->progressPercentageFor($student));
        $this->assertFalse($course->isEligibleForCertificate($student));
        $this->assertDatabaseMissing('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    /**
     * Test 3: Certificate unlocks immediately when both videos are completed and all quizzes are passed.
     */
    public function test_certificate_unlocks_when_all_videos_completed_and_quiz_passed(): void
    {
        $school = School::first();
        $category = Category::first();

        $course = Course::create([
            'school_id' => $school->id,
            'category_id' => $category->id,
            'school_name' => $school->name,
            'title' => 'DevOps Automation CI/CD',
            'slug' => 'devops-automation-cicd',
            'description' => 'Materi DevOps komprehensif',
            'status' => 'published',
        ]);

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Setup Pipeline GitHub Actions',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 600,
            'sort_order' => 1,
            'status' => 'published',
        ]);

        $q = QuizQuestion::create([
            'lesson_id' => $lesson->id,
            'question' => 'File konfigurasi workflow GitHub Actions bertipe apa?',
            'options' => ['YAML', 'JSON', 'XML'],
            'correct_answer_index' => 0,
        ]);

        $student = User::create([
            'name' => 'Dimas Arya',
            'email' => 'dimas@edutube.org',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => $school->id,
            'school_name' => $school->name,
            'is_active' => true,
        ]);

        // Complete video 100%
        $this->actingAs($student)->postJson(route('student.lesson.progress', $lesson->id), [
            'completed' => true,
            'last_watched_second' => 600,
        ])->assertStatus(200);

        // Certificate should NOT be issued yet because quiz is not done
        $this->assertDatabaseMissing('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // Now submit the quiz and pass!
        $quizResp = $this->actingAs($student)->postJson(route('student.lesson.quiz', $lesson->id), [
            'answers' => [
                $q->id => 0,
            ],
        ]);

        $quizResp->assertStatus(200);
        $quizResp->assertJson([
            'success' => true,
            'passed' => true,
        ]);

        // JSON response should now include the unlocked certificate!
        $this->assertNotNull($quizResp->json('certificate'));
        $certCode = $quizResp->json('certificate.code');
        $this->assertNotEmpty($certCode);

        // Database must have the certificate
        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'certificate_code' => $certCode,
        ]);

        // Verification view for the certificate is 200 OK
        $certResponse = $this->get(route('student.certificate', $certCode));
        $certResponse->assertStatus(200);
        $certResponse->assertSee($student->name);
        $certResponse->assertSee($course->title);
    }

    /**
     * Test 4: For course with no quizzes, certificate unlocks when all videos are completed.
     */
    public function test_certificate_unlocks_for_course_without_quizzes(): void
    {
        $school = School::first();
        $category = Category::first();

        $course = Course::create([
            'school_id' => $school->id,
            'category_id' => $category->id,
            'school_name' => $school->name,
            'title' => 'Pengenalan UI/UX Desain Figma',
            'slug' => 'pengenalan-uiux-desain-figma',
            'description' => 'Kursus figma pengantar tanpa kuis',
            'status' => 'published',
        ]);

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Dasar Frame & AutoLayout',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 300,
            'sort_order' => 1,
            'status' => 'published',
        ]);

        $student = User::create([
            'name' => 'Fani Safitri',
            'email' => 'fani@edutube.org',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => $school->id,
            'school_name' => $school->name,
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->postJson(route('student.lesson.progress', $lesson->id), [
            'completed' => true,
            'last_watched_second' => 300,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'course_completed' => true,
        ]);

        $this->assertNotNull($response->json('certificate'));
        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }
}
