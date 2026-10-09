<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'superadmin@edutube.org',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('super_admin', auth()->user()->role);
    }

    public function test_student_login_redirects_to_student_dashboard(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'student@edutube.org',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('student', auth()->user()->role);
    }

    public function test_quick_switch_role_authenticates_target_role(): void
    {
        // Switch to Super Admin
        $respSuper = $this->get(route('switch-role', 'super_admin'));
        $respSuper->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(auth()->user()->isSuperAdmin());

        // Switch to School Admin
        $respSchool = $this->get(route('switch-role', 'school_admin'));
        $respSchool->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(auth()->user()->isSchoolAdmin());

        // Switch to Student
        $respStudent = $this->get(route('switch-role', 'student'));
        $respStudent->assertRedirect(route('student.dashboard'));
        $this->assertTrue(auth()->user()->isStudent());
    }

    public function test_student_is_forbidden_from_accessing_admin_panel(): void
    {
        $student = User::where('role', 'student')->first();

        $response = $this->actingAs($student)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_school_admin_can_access_course_and_curriculum_management(): void
    {
        $schoolAdmin = User::where('role', 'school_admin')->first();

        // Admin Dashboard
        $responseDash = $this->actingAs($schoolAdmin)->get(route('admin.dashboard'));
        $responseDash->assertStatus(200);

        // Courses
        $responseCourses = $this->actingAs($schoolAdmin)->get(route('admin.courses.index'));
        $responseCourses->assertStatus(200);

        // Curriculum
        $responseCurriculum = $this->actingAs($schoolAdmin)->get(route('admin.curriculum.index'));
        $responseCurriculum->assertStatus(200);

        // Students Telemetry
        $responseStudents = $this->actingAs($schoolAdmin)->get(route('admin.students.index'));
        $responseStudents->assertStatus(200);
    }

    public function test_school_admin_is_forbidden_from_super_admin_exclusive_areas(): void
    {
        $schoolAdmin = User::where('role', 'school_admin')->first();

        // User Management is 403 Forbidden for School Admin
        $responseUsers = $this->actingAs($schoolAdmin)->get(route('admin.users.index'));
        $responseUsers->assertStatus(403);

        // System Settings is 403 Forbidden for School Admin
        $responseSettings = $this->actingAs($schoolAdmin)->get(route('admin.settings.index'));
        $responseSettings->assertStatus(403);

        // Master Schools is 403 Forbidden for School Admin
        $responseSchools = $this->actingAs($schoolAdmin)->get(route('admin.schools.index'));
        $responseSchools->assertStatus(403);

        // Support & Log can be accessed by School Admin to contact Central Admin
        $responseSupport = $this->actingAs($schoolAdmin)->get(route('admin.support.index'));
        $responseSupport->assertStatus(200);
    }

    public function test_super_admin_has_full_access_to_all_areas(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        // User Management
        $responseUsers = $this->actingAs($superAdmin)->get(route('admin.users.index'));
        $responseUsers->assertStatus(200);
        $responseUsers->assertSee('Manajemen Pengguna');

        // Settings
        $responseSettings = $this->actingAs($superAdmin)->get(route('admin.settings.index'));
        $responseSettings->assertStatus(200);

        // Support
        $responseSupport = $this->actingAs($superAdmin)->get(route('admin.support.index'));
        $responseSupport->assertStatus(200);

        // Courses
        $responseCourses = $this->actingAs($superAdmin)->get(route('admin.courses.index'));
        $responseCourses->assertStatus(200);
    }

    public function test_super_admin_can_create_school_admin_account(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($superAdmin)->post(route('admin.users.store-school-admin'), [
            'name' => 'Ahmad Dahlan, M.Pd',
            'email' => 'ahmad@smkn5.sch.id',
            'school_name' => 'SMKN 5 Surabaya',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@smkn5.sch.id',
            'role' => 'school_admin',
            'school_name' => 'SMKN 5 Surabaya',
        ]);
    }

    public function test_super_admin_can_toggle_user_active_status(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $student = User::where('role', 'student')->first();
        $this->assertTrue($student->is_active);

        // Deactivate student
        $response = $this->actingAs($superAdmin)->post(route('admin.users.toggle-status', $student->id));
        $response->assertSessionHas('success');

        $student->refresh();
        $this->assertFalse($student->is_active);

        // Blocked user cannot log in
        $loginAttempt = $this->post(route('login.post'), [
            'email' => $student->email,
            'password' => 'password',
        ]);
        $loginAttempt->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
