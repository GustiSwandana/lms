<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseBookmark;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\LessonNote;
use App\Models\User;
use App\Services\YouTubeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_dashboard_can_be_rendered(): void
    {
        $response = $this->get(route('student.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('SIAGA BUMI-SSK');
        $response->assertSee('Katalog Kursus Pembelajaran');
    }

    public function test_student_classroom_can_be_rendered(): void
    {
        $course = Course::first();
        $response = $this->get(route('student.classroom', $course->slug));

        $response->assertStatus(200);
        $response->assertSee($course->title);
        $response->assertSee('SILABUS KURSUS');
    }

    public function test_student_classroom_with_empty_course_renders_empty_state(): void
    {
        $category = Category::first();
        $course = Course::create([
            'category_id' => $category->id,
            'title' => 'Kursus Kosong Tanpa Pelajaran',
            'slug' => 'kursus-kosong-tanpa-pelajaran',
            'description' => 'Kursus percobaan tanpa materi video.',
            'status' => 'published',
        ]);

        $response = $this->get(route('student.classroom', $course->slug));

        $response->assertStatus(200);
        $response->assertSee('Kursus Kosong Tanpa Pelajaran');
        $response->assertSee('Materi Video Belum Tersedia');
    }

    public function test_student_can_toggle_lesson_completion(): void
    {
        $student = User::where('role', 'student')->first();
        $lesson = Lesson::first();

        // Clear existing completions for clean test
        LessonCompletion::where('user_id', $student->id)->where('lesson_id', $lesson->id)->delete();

        // Mark complete
        $response = $this->actingAs($student)->postJson(route('student.lesson.toggle', $lesson->id));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'completed' => true,
        ]);
        $this->assertDatabaseHas('lesson_completions', [
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
        ]);

        // Toggle back to incomplete
        $response2 = $this->actingAs($student)->postJson(route('student.lesson.toggle', $lesson->id));
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'completed' => false,
        ]);
        $this->assertDatabaseMissing('lesson_completions', [
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
        ]);
    }

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pustaka Video Pembelajaran');
        $response->assertSee('Total Video Pelajaran');
    }

    public function test_youtube_service_extracts_video_id_correctly(): void
    {
        $urls = [
            'https://www.youtube.com/watch?v=wm5gMKuwSYk' => 'wm5gMKuwSYk',
            'https://youtu.be/wm5gMKuwSYk' => 'wm5gMKuwSYk',
            'https://www.youtube.com/embed/wm5gMKuwSYk' => 'wm5gMKuwSYk',
            'https://www.youtube.com/shorts/wm5gMKuwSYk' => 'wm5gMKuwSYk',
            'https://www.youtube.com/watch?v=wm5gMKuwSYk&t=120s' => 'wm5gMKuwSYk',
            'wm5gMKuwSYk' => 'wm5gMKuwSYk',
        ];

        foreach ($urls as $url => $expectedId) {
            $this->assertEquals($expectedId, YouTubeService::extractVideoId($url));
        }

        $this->assertEquals(
            'https://img.youtube.com/vi/wm5gMKuwSYk/maxresdefault.jpg',
            YouTubeService::getThumbnailUrl('wm5gMKuwSYk')
        );
    }

    public function test_admin_can_store_new_lesson_with_auto_youtube_extraction(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();

        $payload = [
            'course_id' => $course->id,
            'module_title' => 'Modul Uji Coba',
            'title' => 'Pelajaran Ekstraksi YouTube Otomatis',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'description' => 'Materi pelajaran uji coba ekstraksi otomatis.',
            'status' => 'published',
        ];

        $response = $this->actingAs($admin)->post(route('admin.lessons.store'), $payload);
        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'title' => 'Pelajaran Ekstraksi YouTube Otomatis',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
        ]);
    }

    public function test_admin_youtube_parse_ajax_endpoint(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->postJson(route('admin.youtube.parse'), [
            'url' => 'https://www.youtube.com/watch?v=wm5gMKuwSYk',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'video_id' => 'wm5gMKuwSYk',
            'thumbnail_url' => 'https://img.youtube.com/vi/wm5gMKuwSYk/maxresdefault.jpg',
        ]);
    }

    public function test_student_can_store_and_delete_note(): void
    {
        $student = User::where('role', 'student')->first();
        $lesson = Lesson::first();

        // 1. Store Note
        $response = $this->actingAs($student)->postJson(route('student.lesson.notes.store', $lesson->id), [
            'content' => 'Catatan uji coba otomatis pada menit ke 5',
            'timestamp_seconds' => 300,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'formatted_timestamp' => '05:00',
        ]);

        $this->assertDatabaseHas('lesson_notes', [
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'content' => 'Catatan uji coba otomatis pada menit ke 5',
            'timestamp_seconds' => 300,
        ]);

        $note = LessonNote::where('content', 'Catatan uji coba otomatis pada menit ke 5')->first();

        // 2. Delete Note
        $deleteResponse = $this->actingAs($student)->deleteJson(route('student.notes.destroy', $note->id));
        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson(['success' => true]);

        $this->assertDatabaseMissing('lesson_notes', ['id' => $note->id]);
    }

    public function test_student_can_post_comment(): void
    {
        $student = User::where('role', 'student')->first();
        $lesson = Lesson::first();

        $response = $this->actingAs($student)->postJson(route('student.lesson.comments.store', $lesson->id), [
            'content' => 'Pertanyaan uji coba: Apakah ini best practice?',
            'timestamp_str' => 'pada 02:45',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'comment' => [
                'content' => 'Pertanyaan uji coba: Apakah ini best practice?',
                'timestamp_str' => 'pada 02:45',
            ],
        ]);

        $this->assertDatabaseHas('lesson_comments', [
            'lesson_id' => $lesson->id,
            'content' => 'Pertanyaan uji coba: Apakah ini best practice?',
        ]);
    }

    public function test_student_can_toggle_course_bookmark(): void
    {
        $student = User::where('role', 'student')->first();
        $course = Course::first();

        // Clear existing bookmark if any
        CourseBookmark::where('user_id', $student->id)->where('course_id', $course->id)->delete();

        // Bookmark
        $response = $this->actingAs($student)->postJson(route('student.course.bookmark', $course->slug));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'bookmarked' => true,
        ]);
        $this->assertDatabaseHas('course_bookmarks', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // Unbookmark
        $response2 = $this->actingAs($student)->postJson(route('student.course.bookmark', $course->slug));
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'bookmarked' => false,
        ]);
        $this->assertDatabaseMissing('course_bookmarks', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_admin_course_crud_operations(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $category = Category::first();

        // 1. Index
        $indexResponse = $this->actingAs($admin)->get(route('admin.courses.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Manajer Kursus Pembelajaran');

        // 2. Store
        $storeResponse = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'category_id' => $category->id,
            'title' => 'Kursus Baru Buatan Admin',
            'description' => 'Deskripsi kursus baru yang diinput via form.',
            'instructor_name' => 'Instruktur Handal',
            'instructor_title' => 'Senior Tech Lead',
            'status' => 'published',
        ]);

        $storeResponse->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', [
            'title' => 'Kursus Baru Buatan Admin',
            'slug' => 'kursus-baru-buatan-admin',
        ]);

        $createdCourse = Course::where('slug', 'kursus-baru-buatan-admin')->first();

        // 3. Update
        $updateResponse = $this->actingAs($admin)->put(route('admin.courses.update', $createdCourse->id), [
            'category_id' => $category->id,
            'title' => 'Kursus Baru Buatan Admin (Diperbarui)',
            'description' => 'Deskripsi kursus yang telah diedit.',
            'instructor_name' => 'Instruktur Handal',
            'instructor_title' => 'Senior Tech Lead',
            'status' => 'published',
        ]);

        $updateResponse->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', [
            'id' => $createdCourse->id,
            'title' => 'Kursus Baru Buatan Admin (Diperbarui)',
        ]);

        // 4. Delete
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.courses.destroy', $createdCourse->id));
        $deleteResponse->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseMissing('courses', [
            'id' => $createdCourse->id,
        ]);
    }

    public function test_admin_can_batch_import_playlist(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();

        $response = $this->actingAs($admin)->post(route('admin.youtube.import-playlist'), [
            'course_id' => $course->id,
            'playlist_url' => 'https://www.youtube.com/playlist?list=PL4cUxeGkcC9gUxtBLGi5303',
            'module_title' => 'Modul Sinkronisasi Playlist YouTube',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'module_title' => 'Modul Sinkronisasi Playlist YouTube',
        ]);
    }

    public function test_student_learning_paths_can_be_rendered(): void
    {
        $response = $this->get(route('student.paths'));
        $response->assertStatus(200);
        $response->assertSee('Alur Belajar Terstruktur');
        $response->assertSee('Roadmap Pembelajaran');
    }

    public function test_student_analytics_can_be_rendered(): void
    {
        $student = User::where('role', 'student')->first();
        $response = $this->actingAs($student)->get(route('student.analytics'));
        $response->assertStatus(200);
        $response->assertSee('Analitik Perkembangan Siswa');
        $response->assertSee('Total Jam Belajar');
    }

    public function test_admin_curriculum_builder_operations(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $course = Course::first();
        $lesson = Lesson::first();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.curriculum.index', ['course_id' => $course->id]));
        $response->assertStatus(200);
        $response->assertSee('Penyusun Kurikulum & Modul', false);

        // 2. Update Module Assignment
        $updateResponse = $this->actingAs($admin)->post(route('admin.curriculum.update-module'), [
            'lesson_id' => $lesson->id,
            'module_title' => 'Modul Reorganisasi Kurikulum',
            'sort_order' => 99,
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'module_title' => 'Modul Reorganisasi Kurikulum',
            'sort_order' => 99,
        ]);
    }

    public function test_admin_students_manager_can_be_rendered(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get(route('admin.students.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajer Siswa & Mahasiswa', false);
    }

    public function test_admin_settings_can_be_rendered_and_updated(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Platform LMS');

        // 2. Update
        $updateResponse = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'site_name' => 'EduTube LMS Pro',
            'support_email' => 'support@edutube.org',
            'site_tagline' => 'Next-Gen Platform',
        ]);

        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success');
    }

    public function test_admin_docs_and_support_can_be_rendered(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        // Docs
        $docsResponse = $this->actingAs($admin)->get(route('admin.docs.index'));
        $docsResponse->assertStatus(200);
        $docsResponse->assertSee('Dokumentasi API & Integrasi YouTube', false);

        // Support
        $supportResponse = $this->actingAs($admin)->get(route('admin.support.index'));
        $supportResponse->assertStatus(200);
        $supportResponse->assertSee('Dukungan Teknis & Diagnostik Sistem', false);

        // Submit Ticket
        $ticketResponse = $this->actingAs($admin)->post(route('admin.support.ticket'), [
            'subject' => 'Kendala Uji Coba Video',
            'description' => 'Penjelasan kendala teknis simulasi tiket.',
            'priority' => 'sedang',
        ]);

        $ticketResponse->assertRedirect();
        $ticketResponse->assertSessionHas('success');
    }

    public function test_classroom_renders_without_url_exception_when_all_lessons_completed(): void
    {
        $student = User::where('role', 'student')->first();
        $course = Course::has('lessons')->first();

        // Mark all lessons completed for this student
        foreach ($course->lessons as $lesson) {
            LessonCompletion::firstOrCreate([
                'user_id' => $student->id,
                'lesson_id' => $lesson->id,
            ]);
        }

        // Delete certificate if exists to test 100% render without pre-existing certificate
        Certificate::where('user_id', $student->id)->where('course_id', $course->id)->delete();

        $firstLesson = $course->lessons->first();
        $response = $this->actingAs($student)->get(route('student.classroom.lesson', [
            'course' => $course->slug,
            'lesson' => $firstLesson->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee($course->title);
    }
}
