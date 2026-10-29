<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OptimizationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test 1: Master School CRUD by Super Admin and auto-sync on User/Course.
     */
    public function test_super_admin_can_manage_master_schools_and_syncs_relations(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        // 1. Create School
        $response = $this->actingAs($superAdmin)->post(route('admin.schools.store'), [
            'name' => 'SMKN 5 Surabaya',
            'npsn' => '20500005',
            'address' => 'Jl. Mayjen Prof. Dr. Moestopo No. 40, Surabaya',
        ]);

        $response->assertRedirect(route('admin.schools.index'));
        $this->assertDatabaseHas('schools', [
            'name' => 'SMKN 5 Surabaya',
            'npsn' => '20500005',
        ]);

        $school = School::where('name', 'SMKN 5 Surabaya')->first();

        // 2. Create User with school_id -> school_name is automatically synced
        $user = User::create([
            'name' => 'Guru Surabaya',
            'email' => 'guru@smkn5surabaya.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'school_admin',
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        $this->assertEquals('SMKN 5 Surabaya', $user->fresh()->school_name);

        // 3. Backward-compatible: Create Course with school_name only -> school_id is automatically created/synced
        $course = Course::create([
            'category_id' => Category::first()->id,
            'title' => 'Robotika Industri',
            'description' => 'Materi robotika',
            'school_name' => 'SMKN 5 Surabaya',
            'status' => 'published',
        ]);

        $this->assertEquals($school->id, $course->fresh()->school_id);
    }

    /**
     * Test 2: Bulk Import CSV by School Admin strictly assigns students to own school.
     */
    public function test_school_admin_can_bulk_import_students_via_csv(): void
    {
        $schoolAdmin = User::where('email', 'smkn1@edutube.org')->first(); // SMKN 1 Jakarta

        $csvContent = "name,email,password\n".
            "Dimas Setiawan,dimas@smkn1jakarta.sch.id,Rahasia123!\n".
            "Nabila Syakieb,nabila@smkn1jakarta.sch.id,\n". // Empty password defaults to Siswa123!
            "Sarah Connor,student@edutube.org,password\n"; // Duplicate email, should be skipped

        $file = UploadedFile::fake()->createWithContent('daftar_siswa.csv', $csvContent);

        $response = $this->actingAs($schoolAdmin)->post(route('admin.students.import'), [
            'csv_file' => $file,
            'school_name' => 'Sekolah Rakyat Menengah Atas 44 Minahasa', // Attempt to inject foreign school name
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        // Dimas must be imported with SMP Negeri 1 Bitung
        $this->assertDatabaseHas('users', [
            'name' => 'Dimas Setiawan',
            'email' => 'dimas@smkn1jakarta.sch.id',
            'school_name' => 'SMP Negeri 1 Bitung',
            'role' => 'student',
        ]);

        // Nabila must be imported with default password
        $nabila = User::where('email', 'nabila@smkn1jakarta.sch.id')->first();
        $this->assertNotNull($nabila);
        $this->assertTrue(Hash::check('Siswa123!', $nabila->password));
        $this->assertEquals('SMP Negeri 1 Bitung', $nabila->school_name);
    }

    /**
     * Test 3: Download CSV Template endpoint works.
     */
    public function test_download_student_csv_template(): void
    {
        $schoolAdmin = User::where('email', 'smkn1@edutube.org')->first();

        $response = $this->actingAs($schoolAdmin)->get(route('admin.students.import-template'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="template_import_siswa.csv"');
    }

    /**
     * Test 4: Cache remember and automatic invalidation on Category/Course changes.
     */
    public function test_caching_and_automatic_invalidation(): void
    {
        Cache::forget('active_categories');

        // Prime cache
        $cachedCategories = Cache::remember('active_categories', 900, function () {
            return Category::withCount('courses')->get();
        });

        $this->assertNotEmpty($cachedCategories);
        $this->assertTrue(Cache::has('active_categories'));

        // Creating a new category must automatically forget cache
        Category::create([
            'name' => 'Kategori Baru Cache Test',
            'slug' => 'kategori-baru-cache-test',
        ]);

        $this->assertFalse(Cache::has('active_categories'));
    }

    /**
     * Test 5: Certificate verification and print/download views with QR code.
     */
    public function test_certificate_public_verification_and_download_views(): void
    {
        $student = User::where('email', 'student@edutube.org')->first();
        $course = Course::first();

        $certificate = Certificate::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'certificate_code' => 'CERT-TEST-9999',
            'issued_at' => now(),
        ]);

        // Public verification view
        $response = $this->get(route('student.certificate', $certificate->certificate_code));
        $response->assertStatus(200);
        $response->assertSee('CERT-TEST-9999');
        $response->assertSee($student->name);
        $response->assertSee('CERTIFICATE OF COMPLETION');
        $response->assertSee('STATUS: TERVERIFIKASI');

        // Download view
        $responseDownload = $this->get(route('student.certificate.download', $certificate->certificate_code));
        $responseDownload->assertStatus(200);
        $responseDownload->assertSee('CERT-TEST-9999');
    }

    /**
     * Test 6: School Admin can reset password of own students, but 403 on other schools.
     */
    public function test_school_admin_password_reset_scoping(): void
    {
        $schoolAdmin1 = User::where('email', 'smkn1@edutube.org')->first(); // SMKN 1 Jakarta
        $studentSchool1 = User::where('email', 'student@edutube.org')->first(); // SMKN 1 Jakarta
        $studentSchool2 = User::where('email', 'budi@edutube.org')->first(); // SMAN 2 Bandung

        // Reset own school student -> Success
        $responseSuccess = $this->actingAs($schoolAdmin1)->post(route('admin.students.reset-password', $studentSchool1->id), [
            'new_password' => 'NewPassword123!',
        ]);

        $responseSuccess->assertRedirect();
        $responseSuccess->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewPassword123!', $studentSchool1->fresh()->password));

        // Attempt to reset other school student -> 403 Forbidden
        $responseForbidden = $this->actingAs($schoolAdmin1)->post(route('admin.students.reset-password', $studentSchool2->id), [
            'new_password' => 'HackedPassword123!',
        ]);

        $responseForbidden->assertStatus(403);
    }

    /**
     * Test 7: Rate limiter blocks excessive login attempts.
     */
    public function test_auth_login_rate_limiting(): void
    {
        // Try invalid logins up to limit
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.post'), [
                'email' => 'unknown@user.com',
                'password' => 'wrongpass',
            ]);
        }

        // 6th attempt should be throttled (429)
        $response = $this->post(route('login.post'), [
            'email' => 'unknown@user.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(429);
    }
}
