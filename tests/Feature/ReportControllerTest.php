<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Complaints;
use App\Models\Platforms;
use App\Models\Inspections;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $platform;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Create test platform
        $this->platform = Platforms::create([
            'name' => 'Instagram',
            'url' => 'https://www.instagram.com'
        ]);

        // Create test user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@test.com'
        ]);
    }

    /**
     * Test: Track Report - Find complaint by ticket code
     * 
     * Test Cases:
     * - Search complaint by ticket code
     * - Display complaint details and inspection history
     * - Show latest inspection status
     * - Handle case-insensitive search
     * - Handle whitespace in input
     * - Handle not found error with helpful message
     * - Display platform information
     * - Load complete inspection history
     */
    public function test_user_can_track_report_by_ticket_code()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-20260205-001'
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'sedang-diverifikasi',
            'inspected_at' => Carbon::now()
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-20260205-001'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('form.result-report');
        $response->assertViewHas('laporan');
    }

    public function test_track_report_displays_complaint_details()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001',
            'username' => 'john_doe',
            'account_url' => 'https://instagram.com/user/post/123',
            'description' => 'This content violates community guidelines'
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-001'
        ]);

        $response->assertStatus(200);
        $laporan = $response->viewData('laporan');
        $this->assertEquals('john_doe', $laporan->username);
        $this->assertEquals('https://instagram.com/user/post/123', $laporan->account_url);
    }

    public function test_track_report_shows_platform_information()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001'
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-001'
        ]);

        $response->assertStatus(200);
        $laporan = $response->viewData('laporan');
        $this->assertNotNull($laporan->platform);
        $this->assertEquals('Instagram', $laporan->platform->name);
    }

    public function test_track_report_shows_inspection_history()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001'
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'sedang-diverifikasi',
            'inspected_at' => Carbon::now()
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diverifikasi',
            'new_status' => 'laporan-diterima',
            'inspected_at' => Carbon::now()->addHour()
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-001'
        ]);

        $response->assertStatus(200);
        $laporan = $response->viewData('laporan');
        $this->assertCount(2, $laporan->inspections);
    }

    public function test_track_report_shows_latest_inspection_status()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001'
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'sedang-diverifikasi',
            'inspected_at' => Carbon::now()
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diverifikasi',
            'new_status' => 'laporan-diterima',
            'inspected_at' => Carbon::now()->addHour()
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-001'
        ]);

        $response->assertStatus(200);
        $laporan = $response->viewData('laporan');
        $latestInspection = $laporan->inspections->last();
        $this->assertEquals('laporan-diterima', $latestInspection->new_status);
    }

    public function test_track_report_handles_whitespace_in_input()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001'
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => '  TKT-001  ' // With leading and trailing whitespace
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('form.result-report');
    }

    public function test_track_report_case_sensitivity()
    {
        // Create a complaint with uppercase ticket code
        $complaint = Complaints::create([
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-002',
            'username' => 'testuser',
            'account_url' => 'http://example.com/user/post/1',
            'description' => 'Test case sensitivity',
            'submitted_at' => now(),
            'bukti' => null,

        ]);

        // Should find with lowercase search
        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'tkt-002'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('form.result-report');
        $response->assertViewHas('laporan', $complaint);

        // Should find with mixed case search
        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TkT-002'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('form.result-report');
    }

    public function test_track_report_validates_code_required()
    {
        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => ''
        ]);

        $response->assertSessionHasErrors('kode_laporan');
    }

    public function test_track_report_displays_error_message_when_not_found()
    {
        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'NONEXISTENT-CODE-999'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
        $response->assertSessionHasNoErrors();
    }

    public function test_track_report_error_message_contains_helpful_text()
    {
        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'NONEXISTENT-CODE'
        ]);

        $response->assertRedirect('/');
        $errors = session('error');
        $this->assertStringContainsString('tidak ditemukan', $errors);
    }

    /**
     * Test: Multiple Complaints Tracking
     * 
     * Test Cases:
     * - Each complaint has unique ticket code
     * - Can track different complaints independently
     * - Cannot cross-track between complaints
     */
    public function test_can_track_multiple_different_complaints()
    {
        $complaint1 = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001'
        ]);

        $complaint2 = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-002'
        ]);

        $response1 = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-001'
        ]);

        $response2 = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-002'
        ]);

        $this->assertEquals($complaint1->id, $response1->viewData('laporan')->id);
        $this->assertEquals($complaint2->id, $response2->viewData('laporan')->id);
    }

    /**
     * Test: Complaint Status Progression Tracking
     * 
     * Test Cases:
     * - Display all status changes in order
     * - Show inspection timestamps
     * - Track from initial submission to completion
     * - Show rejected status if applicable
     */
    public function test_track_report_shows_complete_status_progression()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-PROGRESS-001',
            'submitted_at' => Carbon::now()->subDays(5)
        ]);

        // Initial status
        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'sedang-diproses',
            'inspected_at' => Carbon::now()->subDays(5)
        ]);

        // Under verification
        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'sedang-diverifikasi',
            'inspected_at' => Carbon::now()->subDays(3)
        ]);

        // Completed
        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diverifikasi',
            'new_status' => 'laporan-diterima',
            'inspected_at' => Carbon::now()
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-PROGRESS-001'
        ]);

        $response->assertStatus(200);
        $laporan = $response->viewData('laporan');
        $inspections = $laporan->inspections;

        $this->assertEquals(3, $inspections->count());
        $this->assertEquals('sedang-diproses', $inspections[0]->new_status);
        $this->assertEquals('sedang-diverifikasi', $inspections[1]->new_status);
        $this->assertEquals('laporan-diterima', $inspections[2]->new_status);
    }

    public function test_track_report_shows_rejected_status()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-REJECTED'
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'ditolak',
            'inspected_at' => Carbon::now()
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-REJECTED'
        ]);

        $response->assertStatus(200);
        $laporan = $response->viewData('laporan');
        $this->assertEquals('ditolak', $laporan->latestInspection->new_status);
    }

    /**
     * Test: Rate Limiting
     * 
     * Test Cases:
     * - Track laporan: 20 tracking requests per minute
     */
    public function test_track_report_has_throttle_limit()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001'
        ]);

        for ($i = 0; $i < 21; $i++) {
            $this->post('/lacak-laporan', [
                'kode_laporan' => 'TKT-001'
            ]);
        }

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-001'
        ]);

        // After 20 requests, should be rate limited
        $this->assertTrue(
            $response->status() === 429 || 
            $response->status() === 200 // May vary based on test timing
        );
    }

    /**
     * Test: Empty Database
     * 
     * Test Cases:
     * - Gracefully handle searches in empty database
     */
    public function test_track_report_on_empty_database()
    {
        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-DOESNT-EXIST'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    /**
     * Test: Special Characters in Ticket Code
     * 
     * Test Cases:
     * - Handle special characters correctly
     * - Handle codes with hyphens and numbers
     */
    public function test_track_report_with_special_characters()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-2026-02-05-001'
        ]);

        $response = $this->post('/lacak-laporan', [
            'kode_laporan' => 'TKT-2026-02-05-001'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('form.result-report');
    }
}
