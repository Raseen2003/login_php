<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email is required.',
            'email.email'    => 'Please enter a valid email address.',
        ]);

        $email = strtolower(trim($request->email));
        $user  = User::where('email', $email)->first();

        //  Always show generic success to prevent email enumeration
        if (!$user) {
            return back()->with('success', 'If this email exists, a reset link has been sent.');
        }

        //Block soft-deleted users
        if ($user->is_deleted === true) {
            return back()->with('error', 'This account has been deactivated. Please contact admin.');
        }

        //  Generate token
        $token = Str::random(64);

        // Delete old tokens for this email first
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Store new token
        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => $token,
            'created_at' => now(),
        ]);

        $resetUrl = url('/reset-password/' . $token);

        // ✅ Try sending email — catch any mail config errors gracefully
        try {
            Mail::send([], [], function ($message) use ($user, $resetUrl) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email)
                        ->subject('Password Reset Request — TechWyse')
                        ->html("
                            <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
                                <h2 style='color:#f59e0b;'>TechWyse Password Reset</h2>
                                <p>Hello <strong>{$user->name}</strong>,</p>
                                <p>You requested a password reset. Click the button below:</p>
                                <p style='text-align:center;margin:30px 0;'>
                                    <a href='{$resetUrl}'
                                       style='background:#f59e0b;color:#fff;padding:12px 24px;
                                              border-radius:6px;text-decoration:none;font-weight:bold;'>
                                        Reset Password
                                    </a>
                                </p>
                                <p>Or copy this link: <a href='{$resetUrl}'>{$resetUrl}</a></p>
                                <p><strong>This link expires in 1 hour.</strong></p>
                                <p style='color:#999;font-size:12px;'>
                                    If you did not request this, ignore this email.
                                </p>
                            </div>
                        ");
            });

            return back()->with('success', 'Password reset link has been sent to your email!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Exception: ' . $e->getMessage());
            // Mail failed — still show the reset URL on screen for development
            // In production remove the $resetUrl from the error message
            return back()->with('mail_error',
                'Email sending failed. Please check your mail configuration in .env. ' .
                'For testing, use this link directly: ' . $resetUrl
            );
        }
    }
}