<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all non-deleted users for normal user view
        $users = User::where('is_deleted', '!=', true)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('home.index', compact('users'));
    }
}