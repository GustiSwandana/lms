<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoRoleSwitchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_demo_login_page_renders_1_click_easy_access(): void
    {
        $response = $this->get('https://demo.gustiswandana.space/login');

        $response->assertOk();
        $response->assertSee('Akses Cepat Demo (1-Klik Masuk)');
        $response->assertSee('Admin Pusat');
        $response->assertSee('Operator Sekolah');
        $response->assertSee('Guru / Pendidik');
        $response->assertSee('Siswa Pembelajar');
        $response->assertSee('Penyuluh KB / Tamu SSK');
        $response->assertSee('/switch-role/admin_pusat');
        $response->assertSee('/switch-role/operator_sekolah');
        $response->assertSee('/switch-role/guru');
        $response->assertSee('/switch-role/siswa');
        $response->assertSee('/switch-role/penyuluh');
    }

    public function test_production_login_page_does_not_render_easy_access(): void
    {
        $response = $this->get('https://siagabumissk.com/login');

        $response->assertOk();
        $response->assertDontSee('Akses Cepat Demo (1-Klik Masuk)');
        $response->assertDontSee('adminpusat@siagabumi.id');
        $response->assertDontSee('/switch-role/admin_pusat');
    }

    public function test_switch_role_succeeds_on_demo_host(): void
    {
        $roles = [
            'admin_pusat' => 'admin.dashboard',
            'operator_sekolah' => 'admin.dashboard',
            'guru' => 'admin.dashboard',
            'siswa' => 'student.dashboard',
            'penyuluh' => 'student.dashboard',
        ];

        foreach ($roles as $role => $expectedRoute) {
            $response = $this->get("https://demo.gustiswandana.space/switch-role/{$role}");

            $response->assertRedirect(route($expectedRoute));
            $this->assertAuthenticated();
        }
    }

    public function test_switch_role_aborts_403_on_production_domain(): void
    {
        $response = $this->get('https://siagabumissk.com/switch-role/admin_pusat');

        $response->assertForbidden();
    }
}
