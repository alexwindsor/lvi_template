<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{

    public function test_login_page_can_be_reached_when_not_logged_in(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_page_cant_be_reached_when_logged_in(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/login');
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $user->destroy($user->id);
    }

    public function test_user_can_log_in_correctly(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $user->destroy($user->id);
    }

    public function test_errors_when_wrong_type_of_data_is_submitted(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => 'zzzzzz',
            'password' => 'password',
        ]);

        $response->assertInvalid([
            'email' => 'The email field must be a valid email address.'
        ]);
        $response->assertStatus(302);

        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertInvalid([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.'
        ]);
        $response->assertStatus(302);

        $this->assertGuest();

        $user->destroy($user->id);
    }

    public function test_error_when_incorrect_credentials_are_submitted(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => 'wrong@email.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertInvalid([
            'authentication' => 'Incorrect email or password'
        ]);
        $response->assertStatus(302);
        $this->assertGuest();

        $user->destroy($user->id);
    }

}
