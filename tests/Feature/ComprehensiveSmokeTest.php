<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComprehensiveSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected School $schoolA;

    protected School $schoolB;

    protected User $superAdmin;

    protected User $schoolAdmin;

    protected User $student;

    protected Course $course;

    protected Lesson $lesson;

    protected Category $category;

    protected Certificate $certificate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schoolA = School::create([
            'name' => 'SMK Negeri 1 Jakarta',
            'npsn' => '20104928',
            'slug' => 'smkn-1-jakarta',
        ]);

        $this->schoolB = School::create([
            'name' => 'SMA Negeri 2 Bandung',
            'npsn' => '20219384',
            'slug' => 'sman-2-bandung',
        ]);

        $this->superAdmin = User::create([
            'name' => 'Admin Pusat',
            'email' => 'pusat@edutube.id',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->schoolAdmin = User::create([
            'name' => 'Operator SMKN 1',
            'email' => 'operator@smkn1.sch.id',
            'password' => bcrypt('password'),
            'role' => 'school_admin',
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);

        $this->student = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@siswa.smkn1.sch.id',
            'password' => bcrypt('password'),
            'role' => 'student',
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Pemrograman Web',
            'slug' => 'pemrograman-web',
        ]);

        $this->course = Course::create([
            'title' => 'Dasar Laravel 12',
            'slug' => 'dasar-laravel-12',
            'description' => 'Membangun aplikasi web modern dengan Laravel 12',
            'category_id' => $this->category->id,
            'created_by' => $this->superAdmin->id,
            'school_id' => $this->schoolA->id,
            'status' => 'published',
            'instructor_name' => 'Pak Guru',
        ]);

        $this->lesson = Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Pengenalan Routing',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'duration_seconds' => 300,
            'sort_order' => 1,
            'is_free' => true,
        ]);

        $this->certificate = Certificate::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'certificate_code' => 'CERT-SMK-2026-TEST01',
            'issued_at' => now(),
        ]);
    }

    /**
     * Test public and guest endpoints.
     */
    public function test_guest_endpoints_accessible(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/forgot-password')->assertOk();
        $this->get('/sertifikat/'.$this->certificate->certificate_code)->assertOk();
        $this->get('/sertifikat/'.$this->certificate->certificate_code.'/download')->assertOk();
    }

    /**
     * Test student views and features.
     */
    public function test_student_endpoints_accessible(): void
    {
        $this->actingAs($this->student);

        $this->get('/')->assertOk();
        $this->get('/?category=pemrograman-web')->assertOk();
        $this->get('/alur-belajar')->assertOk();
        $this->get('/analitik')->assertOk();
        $this->get('/belajar/'.$this->course->slug)->assertOk();
        $this->get('/belajar/'.$this->course->slug.'/materi/'.$this->lesson->id)->assertOk();
    }

    /**
     * Test school admin views and features.
     */
    public function test_school_admin_endpoints_accessible(): void
    {
        $this->actingAs($this->schoolAdmin);

        $this->get('/admin')->assertOk();
        $this->get('/admin/courses')->assertOk();
        $this->get('/admin/categories')->assertOk();
        $this->get('/admin/students')->assertOk();
        $this->get('/admin/students/import-template')->assertOk();

        // School Admin should NOT access Super Admin exclusive pages
        $this->get('/admin/users')->assertForbidden();
        $this->get('/admin/schools')->assertForbidden();
    }

    /**
     * Test super admin views and features.
     */
    public function test_super_admin_endpoints_accessible(): void
    {
        $this->actingAs($this->superAdmin);

        $this->get('/admin')->assertOk();
        $this->get('/admin/courses')->assertOk();
        $this->get('/admin/categories')->assertOk();
        $this->get('/admin/students')->assertOk();
        $this->get('/admin/users')->assertOk();
        $this->get('/admin/schools')->assertOk();
    }

    /**
     * Test student interactive features: bookmark, comment, completion, profile password.
     */
    public function test_student_interactive_features(): void
    {
        $this->actingAs($this->student);

        // Toggle bookmark
        $this->postJson('/kursus/'.$this->course->slug.'/toggle-bookmark')
            ->assertOk()
            ->assertJson(['success' => true]);

        // Toggle completion
        $this->postJson('/pelajaran/'.$this->lesson->id.'/toggle-complete')
            ->assertOk()
            ->assertJson(['success' => true]);

        // Store comment
        $this->postJson('/pelajaran/'.$this->lesson->id.'/comments', [
            'content' => 'Pertanyaan materi routing ini sangat jelas, terima kasih!',
        ])->assertOk()->assertJson(['success' => true]);

        // Self change password
        $this->post('/profile/update-password', [
            'current_password' => 'password',
            'new_password' => 'SandiBaru2026!',
            'new_password_confirmation' => 'SandiBaru2026!',
        ])->assertRedirect()->assertSessionHas('success');
    }

    /**
     * Test admin CRUD operations for category, course, school, and password reset.
     */
    public function test_admin_crud_operations(): void
    {
        $this->actingAs($this->superAdmin);

        // Create Category
        $this->post('/admin/categories', [
            'name' => 'Data Science & AI',
            'slug' => 'data-science-ai',
            'description' => 'Materi machine learning dan data science',
        ])->assertRedirect()->assertSessionHas('success');

        // Create Course
        $this->post('/admin/courses', [
            'title' => 'Python untuk Machine Learning',
            'category_id' => $this->category->id,
            'description' => 'Belajar dasar Python',
            'school_id' => $this->schoolA->id,
            'instructor_name' => 'Dr. Data',
            'status' => 'published',
        ])->assertRedirect()->assertSessionHas('success');

        // Create School
        $this->post('/admin/schools', [
            'name' => 'SMK Negeri 5 Surabaya',
            'npsn' => '20531234',
            'address' => 'Jl. Mayjen Sungkono Surabaya',
        ])->assertRedirect()->assertSessionHas('success');

        $newSchool = School::where('name', 'SMK Negeri 5 Surabaya')->first();
        $this->assertNotNull($newSchool);

        // Toggle School Status
        $this->post('/admin/schools/'.$newSchool->id.'/toggle-status')
            ->assertRedirect()->assertSessionHas('success');

        // Reset student password
        $this->post('/admin/students/'.$this->student->id.'/reset-password', [
            'new_password' => 'ResetSandi123!',
        ])->assertRedirect()->assertSessionHas('success');
    }

    /**
     * Test quick role switching.
     */
    public function test_role_switcher_works_seamlessly(): void
    {
        $this->get('/switch-role/school_admin')->assertRedirect();
        $this->assertEquals('school_admin', auth()->user()->role);

        $this->get('/switch-role/super_admin')->assertRedirect();
        $this->assertEquals('super_admin', auth()->user()->role);

        $this->get('/switch-role/student')->assertRedirect();
        $this->assertEquals('student', auth()->user()->role);
    }
}
