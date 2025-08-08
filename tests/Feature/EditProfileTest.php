<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class EditProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_edit_their_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => 'Test Test',
            'email' => 'test@example.com',
            'password' => 'password',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $user = User::find($user->id);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertValid();

        $this->assertEquals($user->username, 'Test Test');
        $this->assertEquals($user->email, 'test@example.com');

        $user->destroy($user->id);
    }

    public function test_user_cannot_edit_their_profile_with_incorrect_data(): void
    {
        $user = User::factory()->create();

        // blank fields
        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => '',
            'email' => '',
            'password' => ''
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field is required.',
            'email' => 'The email field is required.',
            'password' => 'The password field is required.'
        ]);
        $response->assertStatus(302);
        // $response->assertRedirect('/edit_profile');

        // fields too short
        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => 'ab',
            'email' => 'a@a.a',
            'password' => 'asdfasd'
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field must be at least 3 characters.',
            'email' => 'The email field must be at least 6 characters.',
            'password' => 'The password field must be at least 8 characters.',
        ]);

        // fields too long
        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => 'abcdefghijklmnopqrstuvwxyzqwertyz',
            'email' => 'a@abcdefghijklmnopqrstuvwxyzqwert.yabcdefghijklmnopqrstuvwxyzqwertyabcde.fghijklmnopqrstuvwxy.com',
            'password' => 'abcdefghijklmnopqrstuvwxyzqwertyz',
            'new_password' => 'abcdefghijklmnopqrstuvwxyzqwertyabcdefghijklmnopqrstuvwxyzqwertyabcdefghijklmnopqrstuvwxyzqwertyz',
            'new_password_confirmation' => 'abcdefghijklmnopqrstuvwxyzqwertyabcdefghijklmnopqrstuvwxyzqwertyabcdefghijklmnopqrstuvwxyzqwertyz',
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username field must not be greater than 32 characters.',
            'email' => 'The email field must not be greater than 96 characters.',
            'password' => 'The password field must not be greater than 32 characters.',
            'new_password' => 'The new password field must not be greater than 32 characters.',
            'new_password_confirmation' => 'The new password confirmation field must not be greater than 32 characters.',
        ]);

        // invalid username and email
        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => 'a@b',
            'email' => 'abcdef',
            'password' => 'password'
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username must not contain the @ character.',
            'email' => 'The email field must be a valid email address.',
        ]);

        // change username/email to someone else's
        $another_user = User::factory()->create();
        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => $another_user->username,
            'email' => $another_user->email,
            'password' => 'password'
        ]);
        $response->assertSessionHasErrors([
            'username' => 'The username has already been taken.',
            'email' => 'The email has already been taken.'
        ]);
        $another_user->destroy($another_user->id);

        // change password with non-matching new passwords
        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => 'Test Test',
            'email' => $user->email,
            'password' => 'password',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'different',
        ]);
        $response->assertSessionHasErrors([
            'new_password_confirmation' => 'The new password confirmation field must match new password.'
        ]);

        // update data with incorrect password
        $response = $this->actingAs($user)->put('/update_profile', [
            'username' => 'Test Test',
            'email' => $user->email,
            'password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);
        $response->assertSessionHasErrors([
            'password' => 'Incorrect password'
        ]);

        // dd(session('errors'));

        $user->destroy($user->id);
    }
}
