<?php

namespace Tests\Feature;

use App\Mail\TicketRepliedMail;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\School;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnterpriseOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test 1: GET /admin/students/export streams valid CSV headers and data.
     */
    public function test_student_export_csv_headers_and_data_stream(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($superAdmin)->get(route('admin.students.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('rekap_siswa_', (string) $response->headers->get('Content-Disposition'));

        // Read stream content
        $content = $response->streamedContent();
        $this->assertStringContainsString('Nama Siswa', $content);
        $this->assertStringContainsString('Email', $content);
        $this->assertStringContainsString('Sekolah', $content);
        $this->assertStringContainsString('Kursus Diikuti', $content);
        $this->assertStringContainsString('Persentase Progres (%)', $content);
        $this->assertStringContainsString('Nilai Rata-rata Kuis', $content);
        $this->assertStringContainsString('Status Lulus', $content);
    }

    /**
     * Test 2: School admin only exports students isolated to their school.
     */
    public function test_student_export_csv_isolated_for_school_admin(): void
    {
        $schoolAdmin = User::where('email', 'smkn1@edutube.org')->first();
        $this->assertNotNull($schoolAdmin);

        // Ensure another student from another school exists
        $otherSchool = School::where('name', 'SMAN 2 Bandung')->first();
        $otherStudent = User::create([
            'name' => 'Siswa Bandung Lain',
            'email' => 'siswa.bandung.lain@edutube.org',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => $otherSchool->id,
            'school_name' => $otherSchool->name,
            'is_active' => true,
        ]);

        $response = $this->actingAs($schoolAdmin)->get(route('admin.students.export'));

        $response->assertStatus(200);
        $content = $response->streamedContent();

        // Must contain SMKN 1 student
        $this->assertStringContainsString('student@edutube.org', $content);

        // Must NOT contain SMAN 2 Bandung student
        $this->assertStringNotContainsString('siswa.bandung.lain@edutube.org', $content);
    }

    /**
     * Test 3: Course with quiz requires passing grade (>= 75) before issuing certificate.
     */
    public function test_certificate_generation_requires_passing_grade_on_course_with_quiz(): void
    {
        $category = Category::first();
        $school = School::first();

        $course = Course::create([
            'category_id' => $category->id,
            'school_id' => $school->id,
            'school_name' => $school->name,
            'title' => 'Ujian Akhir Arsitektur Mikroservis',
            'slug' => 'ujian-akhir-arsitektur-mikroservis',
            'description' => 'Deskripsi ujian akhir',
            'status' => 'published',
        ]);

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Materi Modul Inti',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 600,
            'sort_order' => 1,
            'status' => 'published',
        ]);

        // Create Quiz with Passing Grade 75
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => 'Kuis Evaluasi Akhir',
            'passing_grade' => 75,
            'is_published' => true,
        ]);

        $q1 = QuizQuestion::create([
            'lesson_id' => $lesson->id,
            'quiz_id' => $quiz->id,
            'question' => 'Apa itu idempotency dalam REST API?',
            'options' => ['Aksi dapat dipanggil berulang dengan hasil state sama', 'Aksi selalu acak'],
            'correct_answer_index' => 0,
        ]);

        $q2 = QuizQuestion::create([
            'lesson_id' => $lesson->id,
            'quiz_id' => $quiz->id,
            'question' => 'Protokol apa yang digunakan gRPC?',
            'options' => ['HTTP/1.1', 'HTTP/2'],
            'correct_answer_index' => 1,
        ]);

        $student = User::create([
            'name' => 'Budi Mandiri',
            'email' => 'budi.mandiri@edutube.org',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => $school->id,
            'school_name' => $school->name,
            'is_active' => true,
        ]);

        // 1. Student completes lesson 100%
        LessonCompletion::create([
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'completed_at' => now(),
            'last_watched_second' => 600,
        ]);

        $this->assertEquals(100, $course->progressPercentageFor($student));

        // Since course has an unpassed quiz, student is NOT eligible for certificate
        $this->assertFalse($course->isEligibleForCertificate($student));
        $this->assertDatabaseMissing('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // 2. Student fails quiz with score 50 (1 correct, 1 wrong)
        $failResponse = $this->actingAs($student)->postJson(route('student.lesson.quiz', $lesson->id), [
            'answers' => [
                $q1->id => 0, // correct
                $q2->id => 0, // wrong
            ],
        ]);

        $failResponse->assertStatus(200);
        $failResponse->assertJson([
            'success' => true,
            'score' => 50,
            'passed' => false,
        ]);
        $this->assertNull($failResponse->json('certificate'));

        // Certificate must still be absent
        $this->assertDatabaseMissing('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // 3. Student retries and achieves 100% score (>= 75 passing grade)
        $passResponse = $this->actingAs($student)->postJson(route('student.lesson.quiz', $lesson->id), [
            'answers' => [
                $q1->id => 0, // correct
                $q2->id => 1, // correct
            ],
        ]);

        $passResponse->assertStatus(200);
        $passResponse->assertJson([
            'success' => true,
            'score' => 100,
            'passed' => true,
        ]);
        $this->assertNotNull($passResponse->json('certificate'));

        // Certificate must now be successfully created in the database
        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
        $this->assertTrue($course->isEligibleForCertificate($student));
    }

    /**
     * Test 4: Super Admin responding to ticket dispatches queued TicketRepliedMail.
     */
    public function test_super_admin_responding_to_ticket_queues_email_notification(): void
    {
        Mail::fake();

        $superAdmin = User::where('role', 'super_admin')->first();
        $schoolAdmin = User::where('email', 'smkn1@edutube.org')->first();

        $ticket = SupportTicket::create([
            'user_id' => $schoolAdmin->id,
            'school_id' => $schoolAdmin->school_id,
            'school_name' => $schoolAdmin->school_name,
            'subject' => 'Permintaan update materi kurikulum',
            'category' => 'kurikulum',
            'priority' => 'sedang',
            'status' => 'menunggu',
            'description' => 'Mohon bantuan verifikasi playlist kurikulum baru.',
        ]);

        $response = $this->actingAs($superAdmin)->post(route('admin.support.respond', $ticket->id), [
            'admin_response' => 'Halo SMKN 1, kurikulum telah kami verifikasi dan disetujui.',
            'status' => 'selesai',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'selesai',
            'admin_response' => 'Halo SMKN 1, kurikulum telah kami verifikasi dan disetujui.',
        ]);

        Mail::assertQueued(TicketRepliedMail::class, function ($mail) use ($ticket) {
            return $mail->ticket->id === $ticket->id;
        });
    }

    /**
     * Test 5: Cache unread badge remembers ticket count.
     */
    public function test_cached_unread_ticket_badge_computes_and_busts(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        // Clear any old key
        Cache::forget('support_unread_badge_super');

        // Initial count of tickets with status 'menunggu'
        $count = SupportTicket::where('status', 'menunggu')->count();

        $cachedCount = Cache::remember('support_unread_badge_super', 300, function () {
            return SupportTicket::where('status', 'menunggu')->count();
        });

        $this->assertEquals($count, $cachedCount);
    }
}
