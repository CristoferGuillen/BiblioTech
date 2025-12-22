<?php

namespace App\Http\Controllers;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{


    public function index()
    {
        $user = Auth::user();
       
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'librarian':
                return redirect()->route('librarian.dashboard');
            case 'member':
                return redirect()->route('member.dashboard');
            default:
                return redirect()->route('unauthorized');
        }
    }
}