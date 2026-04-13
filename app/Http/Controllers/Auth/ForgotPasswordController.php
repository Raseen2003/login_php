<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

        // Always show success to prevent email enumeration
        if (!$user) {
            return back()->with('success', 'If this email exists, a reset link has been sent.');
        }

        // Block soft-deleted users
        if ($user->is_deleted === true) {
            return back()->with('error', 'This account has been deactivated. Please contact admin.');
        }

        // Generate token and store in password_reset_tokens table
        $token = Str::random(64);

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => $token,
            'created_at' => now(),
        ]);

        $resetUrl = url('/reset-password/' . $token);

        // Send email
        Mail::send([], [], function ($message) use ($user, $resetUrl) {
            $message->to($user->email)
                    ->subject('Password Reset Request — TechWyse')
                    ->html("
                        <h3>Password Reset Request</h3>
                        <p>Hello {$user->name},</p>
                        <p>Click the link below to reset your password:</p>
                        <p><a href='{$resetUrl}'>{$resetUrl}</a></p>
                        <p>This link expires in <strong>1 hour</strong>.</p>
                        <p>If you did not request this, ignore this email.</p>
                    ");
        });

        return back()->with('success', 'Password reset link has been sent to your email!');
    }
}