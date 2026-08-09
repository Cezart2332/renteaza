<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::with(['vehicles', 'documents'])->get();
        return Inertia::render('Admin/Dashboard', [
            'users' => $users,
        ]);
    }
}