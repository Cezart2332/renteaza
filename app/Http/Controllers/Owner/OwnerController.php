<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OwnerController extends Controller
{
    public function dashboard(Request $request)
    {
        $selectedRole = $request->input('selectedRole', 'Client');
        Log::info('Owner dashboard accessed', ['selectedRole' => $selectedRole]);
        return Inertia::render('Owner/Dashboard', [
            'selectedRole' => $selectedRole,
        ]);
    }
}
