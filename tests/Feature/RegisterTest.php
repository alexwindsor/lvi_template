<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_correct_data(): void
    {
        $response = $this->post('/register', [
            'username' => 'Test Test',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertAuthenticated();

        User::where('email', 'test@example.test')->delete();
    }

    public function test_user_cannot_register_with_incorrect_data(): void
    {
        // blank fields
        $response = $this->post('/register', [
            'username' => null,
            'email' => null,
            'password' => null,
            'password_confirmation' => null,
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field is required.',
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
            'password_confirmation' => 'The password confirmation field is required.'
        ]);
        $response->assertStatus(302);

        // fields too short
        $response = $this->post('/register', [
            'username' => 'ab',
            'email' => 'a@a.a',
            'password' => 'asdfasd',
            'password_confirmation' => 'asdfasd',
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field must be at least 3 characters.',
            'email' => 'The email field must be at least 6 characters.',
            'password' => 'The password field must be at least 8 characters.',
            'password_confirmation' => 'The password confirmation field must be at least 8 characters.'
        ]);

        // fields too long
        $response = $this->post('/register', [
            'username' => 'abcdefghijklmnopqrstuvwxyzqwertyz',
            'email' => 'a@abcdefghijklmnopqrstuvwxyzqwert.yabcdefghijklmnopqrstuvwxyzqwertyabcde.fghijklmnopqrstuvwxy.com',
            'password' => 'abcdefghijklmnopqrstuvwxyzqwertyz',
            'password_confirmation' => 'abcdefghijklmnopqrstuvwxyzqwertyz',
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field must not be greater than 32 characters.',
            'email' => 'The email field must not be greater than 96 characters.',
            'password' => 'The password field must not be greater than 32 characters.',
            'password_confirmation' => 'The password confirmation field must not be greater than 32 characters.',
        ]);

        // invalid username and email
        $response = $this->post('/register', [
            'username' => 'a@b',
            'email' => 'abcdef',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username must not contain the @ character.',
            'email' => 'The email field must be a valid email address.'
        ]);

        // username and email taken
        $another_user = User::factory()->create();
        $response = $this->post('/register', [
            'username' => $another_user->username,
            'email' => $another_user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username has already been taken.',
            'email' => 'The email has already been taken.'
        ]);
        $another_user->destroy($another_user->id);

        // password and password_confirmation don't match
        $response = $this->post('/register', [
            'username' => 'test',
            'email' => 'a@b.co',
            'password' => 'password',
            'password_confirmation' => 'different'
        ]);
        $response->assertSessionHasErrors([
            'password_confirmation' => 'The password confirmation field must match password.'
        ]);

        // dd(session('errors'));
        $this->assertGuest();
    }
}
