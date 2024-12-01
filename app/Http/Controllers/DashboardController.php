<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard', [
            'user' => Auth::user()
        ]);
    }
}