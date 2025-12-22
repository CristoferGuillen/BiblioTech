<?php

namespace App\Http\Controllers;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{


    public function index()
    {
        $user = Auth::user();
       
        if ($user->role === 'member') {
            return view('dashboard.member');
        } elseif ($user->role === 'librarian') {
            return view('dashboard.librarian');
        } elseif ($user->role === 'admin') {
            return view('dashboard.admin');
        } 
         return redirect()->route('login');
    }
}
