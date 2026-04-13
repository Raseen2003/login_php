<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        // Get all non-deleted users, with optional search
        $users = User::where('is_deleted', '!=', true)
                     ->when($search, function ($query) use ($search) {
                         $query->where(function ($q) use ($search) {
                             $q->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                         });
                     })
                     ->orderBy('created_at', 'desc')
                     ->get();

        $totalUsers  = User::where('is_deleted', '!=', true)->count();
        $totalAdmins = User::where('is_deleted', '!=', true)->where('role', 'admin')->count();

        return view('admin.dashboard', compact('users', 'search', 'totalUsers', 'totalAdmins'));
    }
}