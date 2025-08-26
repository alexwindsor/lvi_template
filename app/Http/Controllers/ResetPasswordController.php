<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Inertia\Inertia;


class ResetPasswordController extends Controller
{


    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'min:6', 'max:96'],
        ]);

        // Sends reset link to email if user exists
        Password::sendResetLink(
            $request->only('email')
        );

        return redirect('/login?reset_link_sent');
    }


    public function checkTokenBeforeResetPasswordPage(Request $request): \Inertia\Response
    {
        $request->validate([
            'email' => ['required', 'email', 'min:6', 'max:96']
        ]);
        // 'token' => ['required'],

        if (! $this->validateResetToken($request->email, $request->token)) {
            return Inertia::render('Auth/Login', ['forgot_password' => true]);
        }

        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->token,
        ]);

    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'email' => ['required', 'string', 'min:8', 'max:32'],
            'token' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'max:32'],
            'new_password_confirmation' => ['required', 'string', 'min:8', 'max:32', 'same:new_password'],
        ]);


        if (! $this->validateResetToken($request->email, $request->token)) {
            return Inertia::render('Auth/Login', ['forgot_password' => true]);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect('/login?email=' . $request->email);
    }


    private function validateResetToken($email, $token) : bool
    {

        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $reset) return false;

        // Check token match (tokens are hashed in DB since Laravel 9+)
        $isValid = Hash::check($token, $reset->token);

        if (! $isValid) return false;

        // Check expiration (Laravel default = 60 minutes)
        $expires = Carbon::parse($reset->created_at)->addMinutes(config('auth.passwords.users.expire', 60));

        if (Carbon::now()->greaterThan($expires)) return false;

        return true;
    }


}
