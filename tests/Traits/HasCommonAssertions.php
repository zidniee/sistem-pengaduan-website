<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

/**
 * Trait HasCommonAssertions
 * 
 * Provides reusable assertion methods for common test scenarios
 * Makes tests more readable and maintainable
 */
trait HasCommonAssertions
{
    /**
     * Assert that response is a successful view with expected view name
     * 
     * @param TestResponse $response
     * @param string $viewName
     * @return void
     */
    protected function assertSuccessfulView(TestResponse $response, string $viewName): void
    {
        $response->assertStatus(200);
        $response->assertViewIs($viewName);
    }

    /**
     * Assert that response is a successful view with specific data keys
     * 
     * @param TestResponse $response
     * @param string $viewName
     * @param array $dataKeys
     * @return void
     */
    protected function assertViewWithData(TestResponse $response, string $viewName, array $dataKeys): void
    {
        $this->assertSuccessfulView($response, $viewName);
        
        foreach ($dataKeys as $key) {
            $response->assertViewHas($key);
        }
    }

    /**
     * Assert that response redirects with session status
     * 
     * @param TestResponse $response
     * @param string|null $route Optional specific route to redirect to
     * @return void
     */
    protected function assertRedirectWithStatus(TestResponse $response, ?string $route = null): void
    {
        if ($route) {
            $response->assertRedirect($route);
        } else {
            $response->assertRedirect();
        }
        $response->assertSessionHas('status');
    }

    /**
     * Assert that response redirects with success message
     * 
     * @param TestResponse $response
     * @param string|null $message Optional message to check
     * @return void
     */
    protected function assertRedirectWithSuccess(TestResponse $response, ?string $message = null): void
    {
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        if ($message) {
            $response->assertSessionHas('success', $message);
        }
    }

    /**
     * Assert that response has validation errors for specific fields
     * 
     * @param TestResponse $response
     * @param array $fields
     * @return void
     */
    protected function assertValidationErrors(TestResponse $response, array $fields): void
    {
        $response->assertSessionHasErrors($fields);
    }

    /**
     * Assert that user is authenticated and redirected
     * 
     * @param TestResponse $response
     * @param \App\Models\User $user
     * @param string|null $redirectRoute
     * @return void
     */
    protected function assertAuthenticatedAndRedirected(
        TestResponse $response,
        $user,
        ?string $redirectRoute = null
    ): void {
        $this->assertAuthenticatedAs($user);
        
        if ($redirectRoute) {
            $response->assertRedirect($redirectRoute);
        } else {
            $response->assertRedirect();
        }
    }

    /**
     * Assert that user is not authenticated
     * 
     * @param TestResponse $response
     * @return void
     */
    protected function assertGuestWithErrors(TestResponse $response): void
    {
        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /**
     * Assert database has record with specific attributes
     * 
     * @param string $table
     * @param array $attributes
     * @return void
     */
    protected function assertDatabaseHasRecord(string $table, array $attributes): void
    {
        $this->assertDatabaseHas($table, $attributes);
    }

    /**
     * Assert file was stored in specific path
     * 
     * @param string $disk
     * @param string $path
     * @return void
     */
    protected function assertFileStored(string $disk, string $path): void
    {
        \Storage::disk($disk)->assertExists($path);
    }

    /**
     * Assert complaint has expected structure and data
     * 
     * @param mixed $complaint
     * @param array $expectedData
     * @return void
     */
    protected function assertComplaintData($complaint, array $expectedData): void
    {
        foreach ($expectedData as $key => $value) {
            $this->assertEquals($value, $complaint->$key);
        }
    }

    /**
     * Assert inspection exists with status change
     * 
     * @param int $complaintId
     * @param string $oldStatus
     * @param string $newStatus
     * @return void
     */
    protected function assertInspectionExists(int $complaintId, string $oldStatus, string $newStatus): void
    {
        $this->assertDatabaseHas('inspections', [
            'complaint_id' => $complaintId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
    }
}
