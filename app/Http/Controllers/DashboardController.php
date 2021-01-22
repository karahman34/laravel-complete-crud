<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function view()
    {
        return view('pages.dashboard', [
            'title' => 'Dashboard',
        ]);
    }
}
