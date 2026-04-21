<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller 
{
    public function index(Request $request)
    {
        $users = User::where('is_deleted', '!=', true)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('home.index', compact('users'));
    }
}