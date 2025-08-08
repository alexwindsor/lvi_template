<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_out(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $response->assertStatus(302);
        $this->assertGuest();
        
        $user->destroy($user->id);
    }
}
