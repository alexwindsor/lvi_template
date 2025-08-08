<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_log_in_correctly(): void
    {
        $user = User::factory()->create();

        // login with email
        $response = $this->post('/login', [
            'username' => null,
            'email' => $user->email,
            'password' => 'password',
            'remember' => false
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/');
        auth()->logout();

        // login with email
        $response = $this->post('/login', [
            'username' => $user->username,
            'email' => null,
            'password' => 'password',
            'remember' => false
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $user->destroy($user->id);
    }

    public function test_errors_when_wrong_data_is_submitted(): void
    {
        // fields are blank
        $response = $this->post('/login', [
            'username' => null,
            'email' => null,
            'password' => null,
            'remember' => null,
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field is required when email is not present.',
            'email' => 'The email field is required when username is not present.',
            'password' => 'The password field is required.',
            'remember' => 'The remember field is required.'
        ]);
        $response->assertStatus(302);

        // fields too short
        $response = $this->post('/login', [
            'username' => 'ab',
            'email' => 'a@b.c',
            'password' => 'abcdefg',
            'remember' => true,
        ]);
        // dd(session('errors'));
        $response->assertSessionHasErrors([
            'username' => 'The username field must be at least 3 characters.',
            'email' => 'The email field must be at least 6 characters.',
            'password' => 'The password field must be at least 8 characters.'
        ]);

        // fields too long
        $response = $this->post('/login', [
            'username' => 'abcdefghijklmnopqrstuvwxyzqwertyz', // 33 characters
            'email' => 'a@abcdefghijklmnopqrstuvwxyzqwert.yabcdefghijklmnopqrstuvwxyzqwertyabcde.fghijklmnopqrstuvwxy.com', // 97 characters
            'password' => 'abcdefghijklmnopqrstuvwxyzqwertyz', // 33 characters
            'remember' => true,
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field must not be greater than 32 characters.',
            'email' => 'The email field must not be greater than 96 characters.',
            'password' => 'The password field must not be greater than 32 characters.'
        ]);

        // invalid username and email
        $response = $this->post('/login', [
            'username' => 'a@b',
            'email' => 'abcdef',
            'password' => 'password',
            'remember' => false,
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username must not contain the @ character.',
            'email' => 'The email field must be a valid email address.',
        ]);

        // dd(session('errors'));
        $this->assertGuest();
    }

    public function test_error_when_incorrect_credentials_are_submitted(): void
    {
        // wrong email
        $response = $this->post('/login', [
            'username' => null,
            'email' => 'wrong@email.com',
            'password' => 'wrongpassword',
            'remember' => false,
        ]);
        $response->assertSessionHasErrors(['authentication' => 'Incorrect email or password']);
        $response->assertStatus(302);
        $this->assertGuest();

        // wrong username
        $response = $this->post('/login', [
            'username' => 'abc',
            'email' => null,
            'password' => 'wrongpassword',
            'remember' => false,
        ]);
        $response->assertSessionHasErrors(['authentication' => 'Incorrect email or password']);
        $response->assertStatus(302);
        $this->assertGuest();
    }
}
