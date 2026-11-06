<?php

namespace Tests\Feature;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_a_pending_explorer_account(): void
    {
        Event::fake([UserRegistered::class]);

        $response = $this->postJson('/api/register', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()->assertJsonPath('user.status', 'pending');
        $this->assertDatabaseHas('users', [
            'email' => 'jean@example.com',
            'role' => 'explorateur',
            'status' => 'pending',
        ]);
        Event::assertDispatched(UserRegistered::class);
    }

    public function test_register_rejects_a_duplicate_email(): void
    {
        User::factory()->create(['email' => 'jean@example.com']);

        $this->postJson('/api/register', [
            'name' => 'Jean',
            'email' => 'jean@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_active_user_can_log_in_and_receives_a_token(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'email', 'role', 'status']]);
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'mauvais'])
            ->assertUnauthorized();
    }

    public function test_pending_and_suspended_users_cannot_log_in(): void
    {
        foreach (['pending', 'suspended'] as $status) {
            $user = User::factory()->create(['status' => $status]);

            $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])
                ->assertForbidden();
        }
    }

    public function test_protected_routes_require_authentication(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_user_can_change_password_only_with_the_current_one(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'sanctum')->putJson('/api/user/password', [
            'current_password' => 'mauvais',
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertUnprocessable();

        $this->actingAs($user, 'sanctum')->putJson('/api/user/password', [
            'current_password' => 'password',
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertOk();
    }
}
