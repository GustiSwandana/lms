<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SskRevisionsFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * 1. Test Admin Pusat can curate guest content (toggle is_visible_to_guest).
     */
    public function test_admin_pusat_can_toggle_course_guest_visibility(): void
    {
        $adminPusat = User::where('role', 'admin_pusat')->first();
        $course = Course::first();
        $initialState = (bool) $course->is_visible_to_guest;

        $response = $this->actingAs($adminPusat)->post(route('admin.courses.toggle-guest-visibility', $course->id));
        $response->assertSessionHas('success');

        $this->assertEquals(! $initialState, (bool) $course->fresh()->is_visible_to_guest);
    }

    /**
     * 2. Test School Operator and Guru cannot toggle guest visibility.
     */
    public function test_operator_and_guru_cannot_toggle_course_guest_visibility(): void
    {
        $operator = User::where('role', 'operator_sekolah')->first();
        $guru = User::where('role', 'guru')->first();
        $course = Course::first();

        $resOp = $this->actingAs($operator)->post(route('admin.courses.toggle-guest-visibility', $course->id));
        $resOp->assertStatus(403);

        $resGuru = $this->actingAs($guru)->post(route('admin.courses.toggle-guest-visibility', $course->id));
        $resGuru->assertStatus(403);
    }

    /**
     * 3. Test Guru can access Course Manager & Curriculum, but CANNOT access Students Management.
     */
    public function test_guru_can_access_courses_but_cannot_access_student_management(): void
    {
        $guru = User::where('role', 'guru')->first();

        // Accessible by Guru
        $this->actingAs($guru)->get(route('admin.courses.index'))->assertStatus(200);
        $this->actingAs($guru)->get(route('admin.curriculum.index'))->assertStatus(200);

        // Management features blocked
        $this->actingAs($guru)->get(route('admin.students.index'))->assertStatus(403);
        $this->actingAs($guru)->get('/admin/users')->assertStatus(403);
        $this->actingAs($guru)->get('/admin/schools')->assertStatus(403);
        $this->actingAs($guru)->get('/admin/settings')->assertStatus(403);
    }

    /**
     * 4. Test Penyuluh / Guest can only view courses where is_visible_to_guest = true.
     */
    public function test_penyuluh_can_only_view_curated_courses(): void
    {
        $penyuluh = User::where('role', 'penyuluh')->first();

        $visibleCourse = Course::where('is_visible_to_guest', true)->first();
        $hiddenCourse = Course::where('is_visible_to_guest', false)->first();

        // Can view curated course
        $this->actingAs($penyuluh)
            ->get(route('student.classroom', $visibleCourse->slug))
            ->assertStatus(200);

        // Cannot view non-curated course
        $this->actingAs($penyuluh)
            ->get(route('student.classroom', $hiddenCourse->slug))
            ->assertStatus(403);
    }

    /**
     * 5. Test Penyuluh / Guest is strictly read-only (no quiz submissions, notes, progress, comments).
     */
    public function test_penyuluh_is_strictly_read_only(): void
    {
        $penyuluh = User::where('role', 'penyuluh')->first();
        $visibleCourse = Course::where('is_visible_to_guest', true)->first();
        $lesson = $visibleCourse->lessons()->first();

        // Progress attempt -> 403
        $this->actingAs($penyuluh)
            ->post(route('student.lesson.progress', $lesson->id), ['seconds' => 120])
            ->assertStatus(403);

        // Note attempt -> 403
        $this->actingAs($penyuluh)
            ->post(route('student.lesson.notes.store', $lesson->id), ['content' => 'Catatan tamu', 'timestamp_seconds' => 30])
            ->assertStatus(403);

        // Comment attempt -> 403
        $this->actingAs($penyuluh)
            ->post(route('student.lesson.comments.store', $lesson->id), ['comment' => 'Komentar tamu'])
            ->assertStatus(403);

        // Toggle complete attempt -> 403
        $this->actingAs($penyuluh)
            ->post(route('student.lesson.toggle', $lesson->id))
            ->assertStatus(403);
    }

    /**
     * 6. Test Landing Page contains synchronized headline, BKKBN/SSK content, and Partner Logos.
     */
    public function test_landing_page_contains_required_sections_and_synced_headline(): void
    {
        $response = $this->get(route('student.dashboard'));
        $response->assertStatus(200);

        // Synced exact headline
        $response->assertSee('Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan');

        // SSK & BKKBN sections
        $response->assertSee('Mengenai BKKBN');
        $response->assertSee('Apa sih SSK?');

        // Certificate feature section
        $response->assertSee('Fitur Unggulan Sertifikat Digital');

        // Partner logos
        $response->assertSee('unima.png');
        $response->assertSee('unpi.png');
        $response->assertSee('ipdn.png');
        $response->assertSee('bkkbn.png');
    }

    /**
     * 7. Test Login Page has synchronized headline and registration CTA.
     */
    public function test_login_page_has_synced_headline_and_register_cta(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response->assertSee('Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan');
        $response->assertSee(route('register'));
        $response->assertSee('Admin Pusat');
        $response->assertSee('Operator Sekolah');
        $response->assertSee('Guru / Pendidik');
        $response->assertSee('Siswa Pembelajar');
        $response->assertSee('Penyuluh / Tamu');
    }
}
