<?php

namespace App\Http\Controllers\Owner;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OwnerController extends Controller
{
    /**
     * Pagina asta randa doar layout-ul, cu zona de continut complet goala, si
     * scria un Log::info la fiecare accesare. Acum trimite un rezumat real.
     *
     * Rolul afisat vine din sesiune (setat de user.dashboard.setRole), nu din
     * query string — altfel comutatorul Client/Proprietar se resetează la
     * fiecare navigare.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Owner/Dashboard', [
            'selectedRole' => session('dashboard_role', 'Client'),
            'stats' => [
                'vehiclesTotal' => $user->vehicles()->count(),
                'vehiclesPending' => $user->vehicles()
                    ->where('status', DocumentStatus::PENDING->value)
                    ->count(),
                'bookingsAsOwner' => $user->bookingsOwner()->count(),
                'bookingsAsClient' => $user->bookingsClient()->count(),
            ],
        ]);
    }
}
