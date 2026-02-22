<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\Platforms;
use App\Models\Complaints;
use App\Models\Inspections;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

/**
 * Trait CreatesTestData
 * 
 * Provides reusable methods for creating test data across test classes
 * Eliminates duplication in test setup and data creation
 */
trait CreatesTestData
{
    /**
     * Create a test user with default or custom attributes
     * 
     * @param array $attributes Custom attributes to override defaults
     * @return User
     */
    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'role' => 'user',
            'password' => Hash::make('password123')
        ], $attributes));
    }

    /**
     * Create a test operator user
     * 
     * @param array $attributes Custom attributes to override defaults
     * @return User
     */
    protected function createOperator(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'name' => 'Test Operator',
            'email' => 'operator@test.com',
            'role' => 'operator',
            'password' => Hash::make('password123')
        ], $attributes));
    }

    /**
     * Create a test platform
     * 
     * @param array $attributes Custom attributes to override defaults
     * @return Platforms
     */
    protected function createPlatform(array $attributes = []): Platforms
    {
        return Platforms::create(array_merge([
            'name' => 'Instagram',
            'url' => 'https://www.instagram.com'
        ], $attributes));
    }

    /**
     * Create multiple platforms
     * 
     * @param int $count Number of platforms to create
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function createPlatforms(int $count = 3)
    {
        return Platforms::factory($count)->create();
    }

    /**
     * Create a test complaint with related data
     * 
     * @param array $attributes Custom attributes to override defaults
     * @return Complaints
     */
    protected function createComplaint(array $attributes = []): Complaints
    {
        $defaults = [
            'user_id' => $this->user->id ?? $this->createUser()->id,
            'platform_id' => $this->platform->id ?? $this->createPlatform()->id,
        ];

        return Complaints::factory()->create(array_merge($defaults, $attributes));
    }

    /**
     * Create a test inspection
     * 
     * @param Complaints $complaint The complaint to inspect
     * @param array $attributes Custom attributes to override defaults
     * @return Inspections
     */
    protected function createInspection(Complaints $complaint, array $attributes = []): Inspections
    {
        $defaults = [
            'complaint_id' => $complaint->id,
            'user_id' => $this->operator->id ?? $this->createOperator()->id,
            'old_status' => 'sedang-diproses',
            'new_status' => 'sedang-diverifikasi',
            'inspected_at' => Carbon::now()
        ];

        return Inspections::create(array_merge($defaults, $attributes));
    }

    /**
     * Create a complaint with inspection history
     * 
     * @param array $complaintAttributes
     * @param array $inspectionAttributes
     * @return array ['complaint' => Complaints, 'inspection' => Inspections]
     */
    protected function createComplaintWithInspection(
        array $complaintAttributes = [],
        array $inspectionAttributes = []
    ): array {
        $complaint = $this->createComplaint($complaintAttributes);
        $inspection = $this->createInspection($complaint, $inspectionAttributes);

        return [
            'complaint' => $complaint,
            'inspection' => $inspection
        ];
    }

    /**
     * Create multiple complaints
     * 
     * @param int $count Number of complaints to create
     * @param array $attributes Custom attributes for all complaints
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function createComplaints(int $count, array $attributes = [])
    {
        $defaults = [
            'platform_id' => $this->platform->id ?? $this->createPlatform()->id
        ];

        return Complaints::factory($count)->create(array_merge($defaults, $attributes));
    }

    /**
     * Create a complaint with specific ticket code
     * 
     * @param string $ticket The ticket code
     * @param array $attributes Additional attributes
     * @return Complaints
     */
    protected function createComplaintWithTicket(string $ticket, array $attributes = []): Complaints
    {
        return $this->createComplaint(array_merge(['ticket' => $ticket], $attributes));
    }
}
