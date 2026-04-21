<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function showForm($token) 
    {
     
        $record = DB::table('password_reset_tokens')  
                    ->where('token', $token)
                    ->first();

        if (!$record) {
            return redirect()->route('forgot.password')
                   ->with('error', 'Invalid or expired reset link. Please request a new one.');
        }

        $createdAt = strtotime($record->created_at);
        if ((time() - $createdAt) > 3600) {
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            return redirect()->route('forgot.password')
                   ->with('error', 'This reset link has expired. Please request a new one.');
        }

        return view('auth.reset-password', compact('token'));
    }

    public function reset(Request $request, $token)
    {
        $request->validate([
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^\S+$/'
            ],
        ], [
            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password.regex'     => 'No spaces allowed in password.',
        ]);

      
        $record = DB::table('password_reset_tokens')
                    ->where('token', $token)
                    ->first();

        if (!$record) {
            return redirect()->route('forgot.password')
                   ->with('error', 'Invalid or expired reset link.');
        }

   
        $createdAt = strtotime($record->created_at);
        if ((time() - $createdAt) > 3600) {
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            return redirect()->route('forgot.password')
                   ->with('error', 'This reset link has expired. Please request a new one.');
        }

      
        $user = User::where('email', $record->email)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

     
        DB::table('password_reset_tokens')->where('token', $token)->delete();

        return redirect()->route('login')
               ->with('success', 'Password reset successful! You can now log in.');
    }
}