<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Always show generic message to prevent email enumeration
        if (!$user) {
            return back()->with('success', 'If this email exists, a reset link has been sent.');
        }

  
        if ($user->is_deleted === true) {
            return back()->with('error', 'This account has been deactivated. Please contact admin.');
        }

  
        $token = Str::random(64);

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => $token,
            'created_at' => now(),
        ]);

        $resetUrl  = url('/reset-password/' . $token);
        $userName  = $user->name;
        $fromEmail = config('mail.from.address');
        $fromName  = config('mail.from.name');

        //  Build HTML email body
        $htmlBody = "
            <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;padding:20px;'>
                <h2 style='color:#f59e0b;border-bottom:2px solid #f59e0b;padding-bottom:10px;'>
                    TechWyse — Password Reset
                </h2>
                <p>Hello <strong>{$userName}</strong>,</p>
                <p>You requested a password reset for your TechWyse account.</p>
                <p>Click the button below to set a new password:</p>
                <div style='text-align:center;margin:30px 0;'>
                    <a href='{$resetUrl}'
                       style='background:#f59e0b;color:#ffffff;padding:14px 28px;
                              border-radius:8px;text-decoration:none;font-weight:bold;
                              font-size:16px;display:inline-block;'>
                        Reset My Password
                    </a>
                </div>
                <p style='font-size:13px;color:#666;'>
                    Or copy and paste this link into your browser:
                </p>
                <p style='font-size:12px;color:#999;word-break:break-all;'>
                    {$resetUrl}
                </p>
                <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
                <p style='font-size:12px;color:#999;'>
                     This link expires in <strong>1 hour</strong>.<br>
                    If you did not request this, you can safely ignore this email.
                </p>
            </div>
        ";

        try {
            //  Use Mail::html() — simpler and more reliable than Mail::send()
            Mail::html($htmlBody, function ($message) use ($user, $fromEmail, $fromName) {
                $message->from($fromEmail, $fromName)
                        ->to($user->email, $user->name)
                        ->subject('Password Reset Request — TechWyse');
            });

            return back()->with('success', 'Password reset link has been sent to your email! Check your inbox (and spam folder).');

        } catch (Exception $e) {
    
            Log::error('Mail failed: ' . $e->getMessage());

            return back()->with('mail_error',
                'Email could not be sent. Error: ' . $e->getMessage() . '<br><br>' .
                '<strong>For testing, use this link directly:</strong><br>' .
                '<a href="' . $resetUrl . '">' . $resetUrl . '</a>'
            );
        }
    }
}