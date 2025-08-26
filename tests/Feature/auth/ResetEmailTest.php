<?php

namespace Tests\Feature\auth;

use App\Notifications\CustomResetPassword;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;

class ResetEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_is_emailed_if_email_is_correct() {

        Notification::fake();

        $user = User::factory()->create(); // create user

        // request password reset email
        $response = $this->post('/forgot-password', [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, CustomResetPassword::class); // check email was sent

        $response->assertStatus(302);
        $response->assertRedirect('/login?reset_link_sent');

        // get an instance of the notification that was sent in order to access the valid token
        $notification = Notification::sent($user, CustomResetPassword::class)->first();

        // check that the link works
        $response = $this->get('/reset-password/' . $notification->token . '?email=' . $user->email);
        $response->assertStatus(200);

        // try resetting the password with invalid data
        $response = $this->post('/reset-password/', [
            'new_password' => 'password',
            'new_password_confirmation' => 'different'
        ]);
        $response->assertSessionHasErrors([
            'new_password_confirmation' => 'The new password confirmation field must match new password.',
        ]);

        // reset the password with valid data
        $response = $this->post('/reset-password/', [
            'email' => $user->email,
            'token' => $notification->token,
            'new_password' => 'the_new_password',
            'new_password_confirmation' => 'the_new_password'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login?email=' . $user->email);

        // check that the password has been updated
        $user = User::where('email', $user->email)->first();
        $this->assertTrue(Hash::check('the_new_password', $user->password));

        $user->destroy($user->id);
    }


    public function test_reset_password_link_is_not_emailed_if_email_is_wrong() {

        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => 'invalid_email@test.com'
        ]);

        Notification::assertNothingSent();

        $response->assertStatus(302);
        $response->assertRedirect('/login?reset_link_sent');
    }






}