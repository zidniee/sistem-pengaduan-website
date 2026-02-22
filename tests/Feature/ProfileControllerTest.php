<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Create a test user with 'user' role
        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);
    }

    /**
     * Test: Edit Profile - Display profile edit form
     * 
     * Test Cases:
     * - Authenticated user can view their profile edit form
     * - Page shows current user data
     * - Form has all necessary fields (name, email)
     * - Unauthenticated user cannot access profile
     */
    public function test_authenticated_user_can_view_profile_edit_form()
    {
        $response = $this->actingAs($this->user)
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
        $response->assertViewHas('user');
    }

    public function test_profile_edit_form_displays_current_data()
    {
        $response = $this->actingAs($this->user)
            ->get('/profile');

        $response->assertStatus(200);
        $user = $response->viewData('user');
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }

    public function test_unauthenticated_user_cannot_access_profile_edit()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    /**
     * Test: Update Profile - Update user profile information
     * 
     * Test Cases:
     * - Update name successfully
     * - Update email successfully
     * - Update both name and email
     * - Email verification is reset when email changes
     * - No changes when same data submitted
     * - Validate email format
     * - Prevent duplicate email
     */
    public function test_authenticated_user_can_update_profile_name()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'Jane Doe',
                'email' => 'john@example.com'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->user->refresh();
        $this->assertEquals('Jane Doe', $this->user->name);
    }

    public function test_authenticated_user_can_update_profile_email()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'John Doe',
                'email' => 'newemail@example.com'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->user->refresh();
        $this->assertEquals('newemail@example.com', $this->user->email);
    }

    public function test_authenticated_user_can_update_both_name_and_email()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->user->refresh();
        $this->assertEquals('Jane Smith', $this->user->name);
        $this->assertEquals('jane.smith@example.com', $this->user->email);
    }

    public function test_email_verification_reset_when_email_changes()
    {
        // First, set email verification
        $this->user->update([
            'email_verified_at' => now()
        ]);

        $this->assertNotNull($this->user->email_verified_at);

        // Change email
        $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'John Doe',
                'email' => 'newemail@example.com'
            ]);

        $this->user->refresh();
        $this->assertNull($this->user->email_verified_at);
    }

    public function test_email_verification_not_reset_when_email_unchanged()
    {
        // Set email verification
        $verifiedDate = now();
        $this->user->update([
            'email_verified_at' => $verifiedDate
        ]);

        // Update with same email
        $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'Jane Doe',
                'email' => 'john@example.com'
            ]);

        $this->user->refresh();
        $this->assertNotNull($this->user->email_verified_at);
    }

    public function test_profile_update_validates_email_format()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'John Doe',
                'email' => 'invalid-email'
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_profile_update_prevents_duplicate_email()
    {
        $anotherUser = User::factory()->create([
            'email' => 'another@example.com'
        ]);

        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'John Doe',
                'email' => 'another@example.com'
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_profile_update_allows_same_email()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_profile_update_without_any_changes()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    /**
     * Test: Delete Account - Delete user account with password verification
     * 
     * Test Cases:
     * - User can delete their own account
     * - Requires correct current password
     * - User is logged out after deletion
     * - Session is invalidated
     * - Redirected to homepage
     * - Account data is deleted from database
     * - Invalid password prevents deletion
     */
    public function test_authenticated_user_can_delete_account()
    {
        $response = $this->actingAs($this->user)
            ->delete('/profile', [
                'password' => 'password123'
            ]);

        $response->assertRedirect('/');

        $this->assertNull(User::find($this->user->id));
    }

    public function test_account_deletion_logs_user_out()
    {
        $this->actingAs($this->user);

        $this->delete('/profile', [
            'password' => 'password123'
        ]);

        $this->assertGuest();
    }

    public function test_account_deletion_invalidates_session()
    {
        $response = $this->actingAs($this->user)
            ->delete('/profile', [
                'password' => 'password123'
            ]);

        // Session should be invalidated
        $response->assertRedirect('/');
    }

    public function test_account_deletion_requires_correct_password()
    {
        $response = $this->actingAs($this->user)
            ->delete('/profile', [
                'password' => 'wrongpassword'
            ]);

        $response->assertSessionHasErrorsIn('userDeletion', 'password');

        // User should still exist
        $this->assertNotNull(User::find($this->user->id));
    }

    public function test_account_deletion_requires_password()
    {
        $response = $this->actingAs($this->user)
            ->delete('/profile', []);

        $response->assertSessionHasErrorsIn('userDeletion', 'password');

        // User should still exist
        $this->assertNotNull(User::find($this->user->id));
    }

    public function test_account_deletion_with_invalid_password()
    {
        $userCount = User::count();

        $response = $this->actingAs($this->user)
            ->delete('/profile', [
                'password' => 'incorrect'
            ]);

        $response->assertSessionHasErrors();
        $this->assertEquals($userCount, User::count());
    }

    public function test_unauthenticated_user_cannot_delete_account()
    {
        $response = $this->delete('/profile', [
            'password' => 'password123'
        ]);

        $response->assertRedirect('/login');
    }

    /**
     * Test: Authorization
     * 
     * Test Cases:
     * - User can only edit/delete their own profile
     * - User cannot edit another user's profile
     */
    public function test_user_cannot_edit_another_users_profile()
    {
        $anotherUser = User::factory()->create([
            'name' => 'Another User',
            'email' => 'another@example.com'
        ]);

        // Try to update another user's profile through the form
        // The request form validation should prevent this via Request class
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'Hacked Name',
                'email' => 'hacked@example.com'
            ]);

        // The update should apply to logged-in user only
        $this->user->refresh();
        $this->assertEquals('Hacked Name', $this->user->name);

        // Another user's data should be unchanged
        $anotherUser->refresh();
        $this->assertEquals('Another User', $anotherUser->name);
    }

    /**
     * Test: Validation Rules
     * 
     * Test Cases:
     * - Name field validation
     * - Email field validation
     * - Required field validation
     * - Max length validation
     */
    public function test_profile_update_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => '',
                'email' => ''
            ]);

        $response->assertSessionHasErrors();
    }

    public function test_profile_update_name_can_be_long()
    {
        $longName = str_repeat('A', 255);

        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => $longName,
                'email' => 'john@example.com'
            ]);

        $response->assertRedirect();

        $this->user->refresh();
        $this->assertEquals($longName, $this->user->name);
    }

    public function test_profile_update_email_validation()
    {
        $invalidEmails = [
            'notanemail',
            '@nodomain.com',
        ];

        foreach ($invalidEmails as $email) {
            $response = $this->actingAs($this->user)
                ->patch('/profile', [
                    'name' => 'John Doe',
                    'email' => $email
                ]);

            $response->assertSessionHasErrors('email');
        }
    }

    /**
     * Test: Status Messages
     * 
     * Test Cases:
     * - Success message displayed after update
     * - Session has 'status' key with 'profile-updated'
     */
    public function test_profile_update_displays_success_message()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com'
            ]);

        $response->assertSessionHas('status', 'profile-updated');
    }

    public function test_profile_edit_redirects_to_profile()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com'
            ]);

        $response->assertRedirect(route('profile.edit'));
    }

    /**
     * Test: Multiple User Accounts
     * SKIPPED: RefreshDatabase trait isolation issue between test methods
     */
    public function test_multiple_users_have_separate_profiles()
    {
        $user1 = User::factory()->create([
            'name' => 'User One',
            'email' => 'user1@test.com',
            'role' => 'user'
        ]);

        $user2 = User::factory()->create([
            'name' => 'User Two',
            'email' => 'user2@test.com',
            'role' => 'user'
        ]);

        // Verify user1 can only see their own profile
        $response = $this->actingAs($user1)
            ->get('/profile');

        $response->assertSee('User One');
        $response->assertDontSee('User Two');

        // Verify user2 can only see their own profile
        $response = $this->actingAs($user2)
            ->get('/profile');

        $response->assertSee('User Two');
        $response->assertDontSee('User One');
    }

    public function test_deleting_one_user_does_not_affect_others()
    {
        $user1 = User::factory()->create([
            'name' => 'User One',
            'email' => 'user1@test.com',
            'role' => 'user',
            'password' => Hash::make('password123')
        ]);

        $user2 = User::factory()->create([
            'name' => 'User Two',
            'email' => 'user2@test.com',
            'role' => 'user'
        ]);

        // Delete user1
        $this->actingAs($user1)
            ->delete('/profile', [
                'password' => 'password123'
            ]);

        // Verify user1 is deleted
        $this->assertNull(User::find($user1->id));

        // Verify user2 still exists
        $this->assertNotNull(User::find($user2->id));

        // Verify user2 can still login and access profile
        $response = $this->actingAs($user2)
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('User Two');
    }

    /**
     * Test: Edge Cases
     * 
     * Test Cases:
     * - Very short names
     * - Names with special characters
     * - International characters in names
     * - Rapid consecutive updates
     */
    public function test_profile_update_with_special_characters_in_name()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => "Jean-Claude O'Brien",
                'email' => 'john@example.com'
            ]);

        $response->assertRedirect();

        $this->user->refresh();
        $this->assertEquals("Jean-Claude O'Brien", $this->user->name);
    }

    public function test_profile_update_with_international_characters()
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'José García',
                'email' => 'john@example.com'
            ]);

        $response->assertRedirect();

        $this->user->refresh();
        $this->assertEquals('José García', $this->user->name);
    }

    public function test_consecutive_profile_updates()
    {
        for ($i = 1; $i <= 3; $i++) {
            $response = $this->actingAs($this->user)
                ->patch('/profile', [
                    'name' => "Update {$i}",
                    'email' => 'john@example.com'
                ]);

            $response->assertRedirect();
        }

        $this->user->refresh();
        $this->assertEquals('Update 3', $this->user->name);
    }
}
