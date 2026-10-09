<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected User $superAdmin;

    protected User $schoolAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'SMKN 1 Jakarta',
            'slug' => 'smkn-1-jakarta',
            'npsn' => '20104928',
            'is_active' => true,
        ]);

        $this->superAdmin = User::create([
            'name' => 'Alex Vance, PhD',
            'email' => 'superadmin@edutube.org',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->schoolAdmin = User::create([
            'name' => 'Bambang S., S.Kom',
            'email' => 'smkn1@edutube.org',
            'password' => bcrypt('password'),
            'role' => 'school_admin',
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);
    }

    /**
     * Test school admin can access support page and see diagnostics.
     */
    public function test_school_admin_can_access_support_page(): void
    {
        $this->actingAs($this->schoolAdmin);

        $response = $this->get('/admin/support');

        $response->assertOk();
        $response->assertSee('Dukungan Teknis & Diagnostik Sistem', false);
        $response->assertSee('SMKN 1 Jakarta');
        $response->assertSee('Ajukan Tiket Dukungan ke Admin Pusat');
    }

    /**
     * Test school admin can submit support ticket to central admin.
     */
    public function test_school_admin_can_submit_ticket_to_central_admin(): void
    {
        $this->actingAs($this->schoolAdmin);

        $payload = [
            'subject' => 'Kendala sinkronisasi playlist materi baru YouTube',
            'category' => 'sinkronisasi_youtube',
            'priority' => 'tinggi',
            'description' => 'Playlist ID PL123456 tidak menampilkan durasi otomatis saat diimpor.',
        ];

        $response = $this->post('/admin/support/ticket', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $this->schoolAdmin->id,
            'school_id' => $this->school->id,
            'school_name' => 'SMKN 1 Jakarta',
            'subject' => 'Kendala sinkronisasi playlist materi baru YouTube',
            'status' => 'menunggu',
            'priority' => 'tinggi',
        ]);

        $ticket = SupportTicket::first();
        $this->assertNotNull($ticket);
        $this->assertNotEmpty($ticket->system_logs);
        $this->assertEquals('Admin Sekolah', $ticket->system_logs['sender_role']);
    }

    /**
     * Test super admin can see school admin ticket in central inbox.
     */
    public function test_super_admin_can_see_tickets_from_schools(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->schoolAdmin->id,
            'school_id' => $this->school->id,
            'school_name' => 'SMKN 1 Jakarta',
            'subject' => 'Pertanyaan Integrasi Video Unlisted',
            'category' => 'teknis',
            'priority' => 'sedang',
            'status' => 'menunggu',
            'description' => 'Apakah video YouTube unlisted dapat diputar oleh seluruh siswa?',
        ]);

        $this->actingAs($this->superAdmin);

        $response = $this->get('/admin/support');

        $response->assertOk();
        $response->assertSee($ticket->ticket_code);
        $response->assertSee('Pertanyaan Integrasi Video Unlisted');
        $response->assertSee('SMKN 1 Jakarta');
        $response->assertSee('Bambang S., S.Kom');
    }

    /**
     * Test super admin can respond to ticket and update status.
     */
    public function test_super_admin_can_respond_to_ticket(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->schoolAdmin->id,
            'school_id' => $this->school->id,
            'school_name' => 'SMKN 1 Jakarta',
            'subject' => 'Kendala pemutaran video di lab komputer',
            'category' => 'teknis',
            'priority' => 'kritis',
            'status' => 'menunggu',
            'description' => 'Layar hitam saat memutar video di Chrome versi 110.',
        ]);

        $this->actingAs($this->superAdmin);

        $response = $this->post('/admin/support/tickets/'.$ticket->id.'/respond', [
            'status' => 'selesai',
            'admin_response' => 'Solusi: Perbarui peramban ke versi terbaru dan pastikan opsi hardware acceleration aktif. Kendala telah diuji dan teratasi.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $ticket->refresh();
        $this->assertEquals('selesai', $ticket->status);
        $this->assertNotNull($ticket->responded_at);
        $this->assertEquals($this->superAdmin->id, $ticket->responded_by);
        $this->assertStringContainsString('Solusi: Perbarui peramban', $ticket->admin_response);
    }

    /**
     * Test school admin can see central admin official response.
     */
    public function test_school_admin_can_view_central_admin_response(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->schoolAdmin->id,
            'school_id' => $this->school->id,
            'school_name' => 'SMKN 1 Jakarta',
            'subject' => 'Permintaan penambahan kategori IoT',
            'category' => 'kurikulum',
            'priority' => 'rendah',
            'status' => 'selesai',
            'description' => 'Mohon tambahkan kategori Internet of Things di LMS.',
            'admin_response' => 'Kategori IoT telah ditambahkan ke sistem oleh Admin Pusat.',
            'responded_by' => $this->superAdmin->id,
            'responded_at' => now(),
        ]);

        $this->actingAs($this->schoolAdmin);

        $response = $this->get('/admin/support');

        $response->assertOk();
        $response->assertSee('Kategori IoT telah ditambahkan ke sistem oleh Admin Pusat.');
        $response->assertSee('Terselesaikan');
    }

    /**
     * Test school admin is forbidden from responding to tickets.
     */
    public function test_school_admin_cannot_respond_to_tickets(): void
    {
        $ticket = SupportTicket::create([
            'user_id' => $this->schoolAdmin->id,
            'school_id' => $this->school->id,
            'school_name' => 'SMKN 1 Jakarta',
            'subject' => 'Tiket Uji Coba',
            'category' => 'teknis',
            'priority' => 'sedang',
            'status' => 'menunggu',
            'description' => 'Deskripsi uji coba.',
        ]);

        $this->actingAs($this->schoolAdmin);

        $response = $this->post('/admin/support/tickets/'.$ticket->id.'/respond', [
            'status' => 'selesai',
            'admin_response' => 'Saya mencoba membalas sendiri.',
        ]);

        $response->assertForbidden();
    }
}
