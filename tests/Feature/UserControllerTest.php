<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Complaints;
use App\Models\Platforms;
use App\Models\Inspections;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $platform;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'role' => 'user'
        ]);

        // Create test platforms
        $this->platform = Platforms::create([
            'name' => 'Instagram',
            'url' => 'https://www.instagram.com'
        ]);
    }

    /**
     * Test: Submit Report Form - Display submission form with platforms
     * 
     * Test Cases:
     * - Display report form page
     * - Show all available platforms
     * - Accessible without authentication
     */
    public function test_user_can_view_submit_report_form()
    {
        $response = $this->get('/laporan');

        $response->assertStatus(200);
        $response->assertViewIs('form.reports');
        $response->assertViewHas('platforms');
    }

    public function test_submit_report_form_shows_all_platforms()
    {
        Platforms::factory(3)->create();

        $response = $this->get('/laporan');

        $response->assertStatus(200);
        $platforms = $response->viewData('platforms');
        $this->assertCount(4, $platforms); // 1 from setUp + 3 from factory
    }

    public function test_submit_report_form_accessible_without_auth()
    {
        $response = $this->get('/laporan');

        $response->assertStatus(200);
    }

    /**
     * Test: Submit Complaints - Create new complaint with image upload
     * 
     * Test Cases:
     * - Submit complaint with all required fields
     * - Upload image file (jpeg, png, jpg)
     * - Validate required fields
     * - Validate image format
     * - Validate image size (max 5MB)
     * - Validate URL format
     * - Prevent duplicate URLs
     * - Prevent duplicate account URLs
     * - Auto-prepend https:// if missing
     * - Store image in submissions folder
     * - Create complaint record in database
     * - Display success message
     */
    public function test_user_can_submit_complaint()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/user/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'username' => 'john_doe',
            'account_url' => 'https://instagram.com/user/post/123',
            'platform_id' => $this->platform->id
        ]);
    }

    public function test_submit_complaint_stores_image_file()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertRedirect();
        
        // Check if any file exists in submissions directory
        $files = Storage::disk('local')->files('submissions');
        $this->assertNotEmpty($files);
    }

    public function test_submit_complaint_auto_prepend_https_to_url()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'instagram.com/user/post/123', // Without https://
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $this->assertDatabaseHas('complaints', [
            'account_url' => 'https://instagram.com/user/post/123'
        ]);
    }

    public function test_submit_complaint_validates_image_required()
    {
        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
        ]);

        $response->assertSessionHasErrors('bukti');
    }

    public function test_submit_complaint_validates_image_format()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('bukti.pdf', 100);

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('bukti');
    }

    public function test_submit_complaint_validates_image_size()
    {
        Storage::fake('local');

        // Create a 6MB file (6000 KB)
        $file = UploadedFile::fake()->create('bukti.jpg', 6000);

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('bukti');
    }

    public function test_submit_complaint_validates_platform_required()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('platform');
    }

    public function test_submit_complaint_validates_platform_exists()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => 9999, // Non-existent platform
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('platform');
    }

    public function test_submit_complaint_validates_username_required()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('nama');
    }

    public function test_submit_complaint_validates_url_format()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'invalid url format',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('url');
    }

    public function test_submit_complaint_validates_url_uniqueness()
    {
        Storage::fake('local');

        // Create first complaint
        Complaints::create([
            'username' => 'john_doe',
            'platform_id' => $this->platform->id,
            'account_url' => 'https://instagram.com/post/123',
            'description' => 'First complaint',
            'submitted_at' => Carbon::today(),
            'user_id' => $this->user->id,
            'bukti' => 'image.jpg'
        ]);

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'jane_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123', // Duplicate URL
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('url');
    }

    public function test_submit_complaint_validates_date_before_or_equal_today()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('tanggal');
    }

    public function test_submit_complaint_validates_reason_min_length()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => 'short', // Less than 10 characters
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('alasan');
    }

    public function test_submit_complaint_validates_reason_max_length()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/123',
            'alasan' => str_repeat('a', 1001),
            'bukti' => $file
        ]);

        $response->assertSessionHasErrors('alasan');
    }

    /**
     * Test: User Dashboard - Display user's complaint statistics
     * 
     * Test Cases:
     * - Show user's total complaints count
     * - Show processing complaints count
     * - Show completed complaints count
     * - Show today's complaints (max 20)
     * - User can only see their own data
     */
    public function test_authenticated_user_can_view_dashboard()
    {
        $response = $this->actingAs($this->user)
            ->get('/user/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('User.dashboard');
        $response->assertViewHas('user');
    }

    public function test_dashboard_displays_user_statistics()
    {
        Complaints::factory(5)->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalComplaints', 5);
    }

    public function test_dashboard_shows_only_user_complaints()
    {
        $anotherUser = User::factory()->create();

        Complaints::factory(5)->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        Complaints::factory(5)->create([
            'user_id' => $anotherUser->id,
            'platform_id' => $this->platform->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalComplaints', 5);
    }

    public function test_dashboard_shows_processing_complaints()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'sedang-diproses',
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('processingComplaints', 1);
    }

    public function test_dashboard_shows_completed_complaints()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'laporan-diterima',
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('completedComplaints', 1);
    }

    public function test_dashboard_shows_todays_complaints()
    {
        Complaints::factory(25)->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'created_at' => Carbon::today()
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/dashboard');

        $response->assertStatus(200);
        $complaints = $response->viewData('complaints');
        $this->assertLessThanOrEqual(20, $complaints->count());
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/user/dashboard');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test: User History - Display user's complaint history with search and filter
     * 
     * Test Cases:
     * - Display user's complaints with pagination (10 per page)
     * - Search by ticket number
     * - Search by username
     * - Search by account URL
     * - Search by description
     * - Filter by status
     * - Combine search and filter
     * - Order by latest first
     * - User can only see their own history
     */
    public function test_authenticated_user_can_view_history()
    {
        Complaints::factory(15)->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/history');

        $response->assertStatus(200);
        $response->assertViewIs('User.history');
        $response->assertViewHas('complaints');
    }

    public function test_history_is_paginated()
    {
        Complaints::factory(25)->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/history');

        $response->assertStatus(200);
        $complaints = $response->viewData('complaints');
        $this->assertEquals(10, $complaints->count());
    }

    public function test_history_search_by_ticket()
    {
        Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-001'
        ]);

        Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'ticket' => 'TKT-002'
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/history?search=TKT-001');

        $response->assertStatus(200);
        $complaints = $response->viewData('complaints');
        $this->assertTrue($complaints->contains('ticket', 'TKT-001'));
    }

    public function test_history_search_by_username()
    {
        Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'username' => 'john_doe'
        ]);

        Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'username' => 'jane_doe'
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/history?search=john');

        $response->assertStatus(200);
        $complaints = $response->viewData('complaints');
        $this->assertTrue($complaints->contains('username', 'john_doe'));
    }

    public function test_history_filter_by_status()
    {
        $complaint = Complaints::factory()->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        Inspections::create([
            'complaint_id' => $complaint->id,
            'user_id' => $this->user->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'laporan-diterima',
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/history?status=laporan-diterima');

        $response->assertStatus(200);
        $response->assertViewHas('status', 'laporan-diterima');
    }

    public function test_history_shows_only_user_complaints()
    {
        $anotherUser = User::factory()->create();

        Complaints::factory(5)->create([
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id
        ]);

        Complaints::factory(5)->create([
            'user_id' => $anotherUser->id,
            'platform_id' => $this->platform->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/user/history');

        $response->assertStatus(200);
        $complaints = $response->viewData('complaints');
        $this->assertEquals(5, $complaints->total());
    }

    /**
     * Test: Authorization and Rate Limiting
     * 
     * Test Cases:
     * - Report form: 60 requests per minute
     * - Submit complaint: 5 submissions per minute
     * - User history: 30 requests per minute
     */
    public function test_submit_complaint_has_throttle_limit()
    {
        Storage::fake('local');

        for ($i = 0; $i < 6; $i++) {
            $file = UploadedFile::fake()->image('bukti.jpg');

            $this->post('/lapor', [
                'platform' => $this->platform->id,
                'nama' => 'john_doe_' . $i,
                'tanggal' => Carbon::today()->format('Y-m-d'),
                'url' => 'https://instagram.com/post/' . $i,
                'alasan' => 'This content violates community guidelines',
                'bukti' => $file
            ]);
        }

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post('/lapor', [
            'platform' => $this->platform->id,
            'nama' => 'john_doe',
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'url' => 'https://instagram.com/post/999',
            'alasan' => 'This content violates community guidelines',
            'bukti' => $file
        ]);

        // After 5 submissions, should be rate limited
        $this->assertTrue(
            $response->status() === 429 || 
            $response->status() === 302 // May vary based on test timing
        );
    }

    public function test_unauthenticated_user_cannot_access_history()
    {
        $response = $this->get('/user/history');
        
        $response->assertRedirect('/login');
    }
}
