<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Client/Dashboard');
    }
}
