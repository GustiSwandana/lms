<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test 1: Admin can access the Unified Course Builder page.
     */
    public function test_admin_can_access_course_builder(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();

        $response = $this->actingAs($admin)->get(route('admin.courses.builder', $course->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.courses.builder');
        $response->assertViewHas('course');
        $response->assertViewHas('quiz');
        $response->assertSee('Cloud Auto-Save Aktif');
    }

    /**
     * Test 2: Admin can initialize a new draft course directly into the builder.
     */
    public function test_admin_can_initialize_draft_course_via_builder(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.courses.builder.create'), [
            'title' => 'Mastering AI Agentic Systems',
        ]);

        $course = Course::where('title', 'Mastering AI Agentic Systems')->first();
        $this->assertNotNull($course);
        $this->assertEquals('draft', $course->status);

        $response->assertRedirect(route('admin.courses.builder', $course->id));
    }

    /**
     * Test 3: Auto-Save updates course information via AJAX PATCH.
     */
    public function test_auto_save_updates_course_info_via_ajax(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();
        $category = Category::latest()->first();

        $response = $this->actingAs($admin)->patchJson(route('admin.courses.builder.info', $course->id), [
            'title' => 'Full-Stack Modern Microservices with Laravel 12',
            'category_id' => $category->id,
            'level' => 'Lanjutan',
            'instructor_name' => 'Dr. Robert Oppen',
            'instructor_title' => 'Distinguished Architect',
            'description' => 'Silabus komprehensif arsitektur mikroservis skala produksi.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'course' => [
                'title' => 'Full-Stack Modern Microservices with Laravel 12',
                'level' => 'Lanjutan',
            ],
        ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Full-Stack Modern Microservices with Laravel 12',
            'level' => 'Lanjutan',
        ]);
    }

    /**
     * Test 4: Smart YouTube Ingestion adds video lesson asynchronously.
     */
    public function test_smart_youtube_ingestion_adds_lesson_via_ajax(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();

        $response = $this->actingAs($admin)->postJson(route('admin.courses.builder.lessons.store', $course->id), [
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'module_title' => 'Modul 1: Konsep Dasar Arsitektur',
            'title' => 'Instalasi Lingkungan Pengembangan',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'lesson' => [
                'youtube_video_id' => 'dQw4w9WgXcQ',
                'title' => 'Instalasi Lingkungan Pengembangan',
                'module_title' => 'Modul 1: Konsep Dasar Arsitektur',
            ],
        ]);

        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'title' => 'Instalasi Lingkungan Pengembangan',
        ]);
    }

    /**
     * Test 5: Reordering lessons via AJAX.
     */
    public function test_reordering_lessons_via_ajax(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();

        $l1 = Lesson::create([
            'course_id' => $course->id,
            'module_title' => 'Modul 1',
            'title' => 'Materi Satu',
            'youtube_url' => 'https://www.youtube.com/watch?v=aaaa1111',
            'youtube_video_id' => 'aaaa1111',
            'sort_order' => 1,
            'status' => 'published',
        ]);

        $l2 = Lesson::create([
            'course_id' => $course->id,
            'module_title' => 'Modul 1',
            'title' => 'Materi Dua',
            'youtube_url' => 'https://www.youtube.com/watch?v=bbbb2222',
            'youtube_video_id' => 'bbbb2222',
            'sort_order' => 2,
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.courses.builder.lessons.reorder', $course->id), [
            'lessons' => [
                ['id' => $l1->id, 'sort_order' => 2, 'module_title' => 'Modul 1'],
                ['id' => $l2->id, 'sort_order' => 1, 'module_title' => 'Modul 1'],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(2, $l1->fresh()->sort_order);
        $this->assertEquals(1, $l2->fresh()->sort_order);
    }

    /**
     * Test 6: Saving quiz settings and questions via AJAX.
     */
    public function test_saving_quiz_settings_and_questions_via_ajax(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();

        // 1. Update quiz settings
        $settingsResponse = $this->actingAs($admin)->postJson(route('admin.courses.builder.quiz.save', $course->id), [
            'title' => 'Ujian Sertifikasi Kompetensi Resmi',
            'passing_grade' => 85,
            'time_limit_minutes' => 30,
            'shuffle_questions' => true,
        ]);

        $settingsResponse->assertStatus(200);
        $settingsResponse->assertJson([
            'success' => true,
            'quiz' => [
                'passing_grade' => 85,
                'time_limit_minutes' => 30,
                'shuffle_questions' => true,
            ],
        ]);

        $quiz = Quiz::where('course_id', $course->id)->first();
        $this->assertEquals(85, $quiz->passing_grade);
        $this->assertEquals(30, $quiz->time_limit_minutes);
        $this->assertTrue($quiz->shuffle_questions);

        // 2. Add question to quiz
        $questionResponse = $this->actingAs($admin)->postJson(route('admin.courses.builder.quiz.questions.store', $course->id), [
            'quiz_id' => $quiz->id,
            'question' => 'Protokol apa yang digunakan oleh WebSocket?',
            'options' => ['TCP dengan handshake HTTP', 'UDP broadcast', 'SMTP', 'FTP'],
            'correct_answer_index' => 0,
            'explanation' => 'WebSocket diawali dengan handshake upgrade HTTP/1.1 lalu full-duplex TCP.',
        ]);

        $questionResponse->assertStatus(200);
        $questionResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('quiz_questions', [
            'quiz_id' => $quiz->id,
            'question' => 'Protokol apa yang digunakan oleh WebSocket?',
            'correct_answer_index' => 0,
        ]);
    }

    /**
     * Test 7: Publishing course fails validation when course has 0 lessons.
     */
    public function test_publish_course_fails_validation_when_no_lessons_exist(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $category = Category::first();

        // Create empty course without lessons
        $emptyCourse = Course::create([
            'category_id' => $category->id,
            'title' => 'Kursus Kosong Tanpa Video',
            'slug' => 'kursus-kosong-tanpa-video',
            'description' => 'Deskripsi kursus yang masih kosong.',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.courses.builder.publish', $emptyCourse->id));

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertNotEmpty($response->json('errors'));
        $this->assertEquals('draft', $emptyCourse->fresh()->status);
    }

    /**
     * Test 8: Publishing course succeeds when valid and unpublish toggles back to draft.
     */
    public function test_publish_course_succeeds_when_valid_and_unpublish_works(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $category = Category::first();

        $course = Course::create([
            'category_id' => $category->id,
            'title' => 'Kursus Lengkap Siap Terbit',
            'slug' => 'kursus-lengkap-siap-terbit',
            'description' => 'Deskripsi materi lengkap dengan silabus pembelajaran.',
            'status' => 'draft',
        ]);

        Lesson::create([
            'course_id' => $course->id,
            'title' => 'Video Materi Inti',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 600,
            'sort_order' => 1,
            'status' => 'published',
        ]);

        // 1. Publish
        $publishResponse = $this->actingAs($admin)->postJson(route('admin.courses.builder.publish', $course->id));
        $publishResponse->assertStatus(200);
        $publishResponse->assertJson([
            'success' => true,
            'status' => 'published',
        ]);
        $this->assertEquals('published', $course->fresh()->status);

        // 2. Unpublish
        $unpublishResponse = $this->actingAs($admin)->postJson(route('admin.courses.builder.unpublish', $course->id));
        $unpublishResponse->assertStatus(200);
        $unpublishResponse->assertJson([
            'success' => true,
            'status' => 'draft',
        ]);
        $this->assertEquals('draft', $course->fresh()->status);
    }
}
