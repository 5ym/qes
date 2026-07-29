<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_laravel_ui_scaffolding_still_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('Login');
        $this->get('/register')->assertOk()->assertSee('Register');
        $this->get('/password/reset')->assertOk();
    }

    public function test_a_user_can_log_in_and_reach_the_dashboard(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/home');

        $this->assertAuthenticatedAs($user);

        $this->get('/home')->assertOk();
    }

    public function test_the_dashboard_is_closed_to_guests(): void
    {
        $this->get('/home')->assertRedirect('/login');
    }

    public function test_the_password_broker_table_was_renamed(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('password_reset_tokens'));
        $this->assertFalse(\Illuminate\Support\Facades\Schema::hasTable('password_resets'));
    }
}
