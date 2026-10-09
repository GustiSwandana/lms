<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonComment;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test 1.A: Multi-Tenancy Scoping - School Admin from School A is rejected with 403
     * when attempting to edit or delete courses from School B.
     */
    public function test_school_admin_cannot_update_or_delete_course_from_another_school(): void
    {
        $schoolAdmin1 = User::where('email', 'smkn1@edutube.org')->first(); // SMKN 1 Jakarta
        $schoolAdmin2 = User::where('email', 'sman2@edutube.org')->first(); // SMAN 2 Bandung

        $courseSchool2 = Course::where('school_name', 'SMAN 2 Bandung')->first();

        // 1. School Admin 1 tries to update School 2's course -> 403 Forbidden
        $responseUpdate = $this->actingAs($schoolAdmin1)->put(route('admin.courses.update', $courseSchool2->id), [
            'category_id' => $courseSchool2->category_id,
            'title' => 'Judul Dibajak Oleh Sekolah Lain',
            'description' => 'Deskripsi dibajak',
            'status' => 'published',
        ]);

        $responseUpdate->assertStatus(403);
        $this->assertDatabaseMissing('courses', [
            'id' => $courseSchool2->id,
            'title' => 'Judul Dibajak Oleh Sekolah Lain',
        ]);

        // 2. School Admin 1 tries to delete School 2's course -> 403 Forbidden
        $responseDelete = $this->actingAs($schoolAdmin1)->delete(route('admin.courses.destroy', $courseSchool2->id));
        $responseDelete->assertStatus(403);
        $this->assertDatabaseHas('courses', [
            'id' => $courseSchool2->id,
        ]);

        // 3. School Admin 1 can update their OWN course -> 302 Redirect (Success)
        $courseSchool1 = Course::where('school_name', 'SMKN 1 Jakarta')->first();
        $responseOwn = $this->actingAs($schoolAdmin1)->put(route('admin.courses.update', $courseSchool1->id), [
            'category_id' => $courseSchool1->category_id,
            'title' => 'Judul Resmi Diperbarui Sekolah 1',
            'description' => 'Deskripsi valid dari pemilik',
            'status' => 'published',
        ]);
        $responseOwn->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', [
            'id' => $courseSchool1->id,
            'title' => 'Judul Resmi Diperbarui Sekolah 1',
        ]);
    }

    /**
     * Test 1.B: Super Admin has global rights and can edit any course.
     */
    public function test_super_admin_can_update_any_course(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $course = Course::first();

        $response = $this->actingAs($superAdmin)->put(route('admin.courses.update', $course->id), [
            'category_id' => $course->category_id,
            'title' => 'Super Admin Global Edit',
            'description' => 'Dikelola oleh pusat',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Super Admin Global Edit',
        ]);
    }

    /**
     * Test 1.C: Student catalog filtering - "Kursus Sekolah Saya" vs "Semua Kursus".
     */
    public function test_student_catalog_can_filter_by_own_school(): void
    {
        $student = User::where('email', 'student@edutube.org')->first(); // SMKN 1 Jakarta

        // 1. All courses view
        $responseAll = $this->actingAs($student)->get(route('student.dashboard'));
        $responseAll->assertStatus(200);
        $responseAll->assertSee('SMKN 1 Jakarta');
        $responseAll->assertSee('SMAN 2 Bandung');

        // 2. My School filter
        $responseMySchool = $this->actingAs($student)->get(route('student.dashboard', ['scope' => 'my_school']));
        $responseMySchool->assertStatus(200);
        $responseMySchool->assertSee('SMKN 1 Jakarta');
        $responseMySchool->assertDontSee('SMAN 2 Bandung');
    }

    /**
     * Test 2.A: Quiz Checkpoint submission saves score, passed flag, and answers in database.
     */
    public function test_student_quiz_submission_persists_attempt_and_score(): void
    {
        $student = User::where('email', 'elena@edutube.org')->first();
        $lesson = Lesson::whereHas('quizzes')->first();
        $answers = [];
        foreach ($lesson->quizzes as $quiz) {
            $answers[$quiz->id] = $quiz->correct_answer_index;
        }

        $response = $this->actingAs($student)->postJson(route('student.lesson.quiz', $lesson->id), [
            'answers' => $answers,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'score' => 100,
            'passed' => true,
        ]);

        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'score' => 100,
            'passed' => 1,
        ]);
    }

    /**
     * Test 2.B: Threaded discussion comments and like toggle persist in database.
     */
    public function test_discussion_comment_and_like_persistence(): void
    {
        $student = User::where('email', 'student@edutube.org')->first();
        $lesson = Lesson::first();

        // 1. Post parent comment
        $commentResponse = $this->actingAs($student)->postJson(route('student.lesson.comments.store', $lesson->id), [
            'content' => 'Pertanyaan uji otomatis dari pipeline test.',
        ]);

        $commentResponse->assertStatus(200);
        $commentResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('lesson_comments', [
            'lesson_id' => $lesson->id,
            'user_id' => $student->id,
            'content' => 'Pertanyaan uji otomatis dari pipeline test.',
            'parent_id' => null,
        ]);

        $parentComment = LessonComment::where('content', 'Pertanyaan uji otomatis dari pipeline test.')->first();

        // 2. Post reply comment
        $replyResponse = $this->actingAs($student)->postJson(route('student.lesson.comments.store', $lesson->id), [
            'content' => 'Balasan tersarang pada komentar induk.',
            'parent_id' => $parentComment->id,
        ]);

        $replyResponse->assertStatus(200);
        $this->assertDatabaseHas('lesson_comments', [
            'lesson_id' => $lesson->id,
            'parent_id' => $parentComment->id,
            'content' => 'Balasan tersarang pada komentar induk.',
        ]);

        // 3. Toggle like
        $likeResponse = $this->actingAs($student)->postJson(route('student.comment.like', $parentComment->id));
        $likeResponse->assertStatus(200);
        $likeResponse->assertJson([
            'success' => true,
            'liked' => true,
            'likes_count' => $parentComment->likes_count + 1,
        ]);

        $this->assertDatabaseHas('comment_likes', [
            'user_id' => $student->id,
            'lesson_comment_id' => $parentComment->id,
        ]);
    }

    /**
     * Test 3 & 4: Resume playback (last_watched_second) and automatic Certificate issuance upon 100% completion.
     */
    public function test_playback_progress_saving_and_automatic_certificate_issuance(): void
    {
        $student = User::where('email', 'elena@edutube.org')->first();
        $course = Course::where('slug', 'dasar-dasar-kontainerisasi-docker-kubernetes')->first();
        $lessons = $course->lessons;

        $this->assertCount(2, $lessons);

        // Complete lesson 1
        $lesson1 = $lessons[0];
        $resp1 = $this->actingAs($student)->postJson(route('student.lesson.progress', $lesson1->id), [
            'completed' => true,
            'last_watched_second' => 450,
        ]);
        $resp1->assertStatus(200);

        $this->assertDatabaseHas('lesson_completions', [
            'user_id' => $student->id,
            'lesson_id' => $lesson1->id,
            'last_watched_second' => 450,
        ]);

        // Submit and pass checkpoint quiz on lesson 1
        $quizQ = QuizQuestion::where('lesson_id', $lesson1->id)->first();
        if ($quizQ) {
            $this->actingAs($student)->postJson(route('student.lesson.quiz', $lesson1->id), [
                'answers' => [
                    $quizQ->id => $quizQ->correct_answer_index,
                ],
            ]);
        }

        // No certificate yet (50% video completion, even if quiz is passed)
        $this->assertDatabaseMissing('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // Complete lesson 2 (100% completion)
        $lesson2 = $lessons[1];
        $resp2 = $this->actingAs($student)->postJson(route('student.lesson.progress', $lesson2->id), [
            'completed' => true,
            'last_watched_second' => 900,
        ]);
        $resp2->assertStatus(200);
        $resp2->assertJson([
            'success' => true,
            'course_completed' => true,
        ]);

        // Certificate must now exist!
        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $certificate = Certificate::where('user_id', $student->id)->where('course_id', $course->id)->first();
        $this->assertNotNull($certificate->certificate_code);

        // Verification route renders cleanly
        $certView = $this->get(route('student.certificate', $certificate->certificate_code));
        $certView->assertStatus(200);
        $certView->assertSee($certificate->certificate_code);
        $certView->assertSee($student->name);
        $certView->assertSee($course->title);
    }

    /**
     * Test 5.A: Security hardening - Quick switch role route returns 403 on production environment.
     */
    public function test_quick_switch_role_returns_403_in_production(): void
    {
        // Simulate production environment
        app()->detectEnvironment(fn () => 'production');
        config(['app.debug' => false]);

        $response = $this->get(route('switch-role', 'super_admin'));
        $response->assertStatus(403);

        // Restore environment
        app()->detectEnvironment(fn () => 'testing');
        config(['app.debug' => true]);
    }

    /**
     * Test 5.B: YouTube Link Health Checker endpoint returns structured JSON status.
     */
    public function test_youtube_health_checker_endpoint(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        // Valid video ID
        $response = $this->actingAs($superAdmin)->postJson(route('admin.youtube.check-health'), [
            'video_url' => 'https://www.youtube.com/watch?v=bMknfKXIFA8',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'video_id',
            'status',
            'message',
        ]);
    }

    /**
     * Test 6.A: Super Admin can add student directly via User Management.
     */
    public function test_super_admin_can_add_student_via_user_management(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($superAdmin)->post(route('admin.users.store-student'), [
            'name' => 'Fajar Pratama',
            'email' => 'fajar@smkn1jakarta.sch.id',
            'school_name' => 'SMKN 1 Jakarta',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Fajar Pratama',
            'email' => 'fajar@smkn1jakarta.sch.id',
            'school_name' => 'SMKN 1 Jakarta',
            'role' => 'student',
            'is_active' => true,
        ]);
    }

    /**
     * Test 6.B: Public registration is completely disabled (returns 404).
     */
    public function test_public_registration_is_disabled(): void
    {
        $responseGet = $this->get('/register');
        $responseGet->assertStatus(404);

        $responsePost = $this->post('/register', [
            'name' => 'Dewi Lestari',
            'email' => 'dewi@sman2bandung.sch.id',
            'school_name' => 'SMAN 2 Bandung',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $responsePost->assertStatus(404);

        $this->assertDatabaseMissing('users', [
            'email' => 'dewi@sman2bandung.sch.id',
        ]);
    }

    /**
     * Test 6.C: Super Admin (Admin Pusat) creates School Operator account.
     */
    public function test_super_admin_creates_school_operator_account(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($superAdmin)->post(route('admin.users.store-school-admin'), [
            'name' => 'Bambang Sudarsono, S.Kom',
            'email' => 'bambang.operator@smkn5surabaya.sch.id',
            'school_name' => 'SMKN 5 Surabaya',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Bambang Sudarsono, S.Kom',
            'email' => 'bambang.operator@smkn5surabaya.sch.id',
            'school_name' => 'SMKN 5 Surabaya',
            'role' => 'school_admin',
            'is_active' => true,
        ]);

        // Non-super admin cannot access this endpoint
        $schoolAdmin = User::where('email', 'smkn1@edutube.org')->first();
        $unauthorizedResponse = $this->actingAs($schoolAdmin)->post(route('admin.users.store-school-admin'), [
            'name' => 'Hacker Admin',
            'email' => 'hacker@school.id',
            'school_name' => 'Hacked School',
            'password' => 'password123',
        ]);
        $unauthorizedResponse->assertStatus(403);
    }

    /**
     * Test 6.D: School Admin (Operator Sekolah) creates student strictly locked to their own school.
     */
    public function test_school_admin_can_add_student_strictly_to_own_school(): void
    {
        $schoolAdmin = User::where('email', 'smkn1@edutube.org')->first(); // SMKN 1 Jakarta

        $response = $this->actingAs($schoolAdmin)->post(route('admin.students.store'), [
            'name' => 'Rizky Siswa SMKN 1',
            'email' => 'rizky@smkn1jakarta.sch.id',
            'password' => 'password123',
            // Even if an attacker attempts to inject another school name, the system forces the operator's school
            'school_name' => 'SMAN 2 Bandung',
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Rizky Siswa SMKN 1',
            'email' => 'rizky@smkn1jakarta.sch.id',
            'school_name' => 'SMKN 1 Jakarta', // Strictly locked to operator's school
            'role' => 'student',
            'is_active' => true,
        ]);
    }
}
