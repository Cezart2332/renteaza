<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DocumentStatus;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Varianta anterioara incarca TOTI utilizatorii cu toate relatiile
     * (User::with(['vehicles','documents'])->get()) si nu afisa nimic
     * actionabil. Acum trimite doar agregate: cate lucruri asteapta aprobare.
     */
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'usersTotal' => User::count(),
                'usersPending' => User::where('status', UserStatus::Pending->value)->count(),
                'vehiclesTotal' => Vehicle::count(),
                'vehiclesPending' => Vehicle::where('status', DocumentStatus::PENDING->value)->count(),
            ],
        ]);
    }

    /**
     * Lista globala de masini — nu exista deloc pana acum, iar adminul trebuia
     * sa stie dinainte cine e proprietarul ca sa ajunga la o masina
     * (Utilizatori -> editare -> vehicule -> documente, patru click-uri).
     */
    public function vehicles(Request $request)
    {
        $filters = $request->input('filters', []);

        $vehicles = Vehicle::query()
            ->with(['owner:id,name,email', 'vehicleType:id,name'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('license_plate', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Vehicles/List', [
            'vehicles' => $vehicles,
            'prevFilters' => $filters,
            'statusOptions' => collect(DocumentStatus::cases())
                ->map(fn (DocumentStatus $s) => [
                    'value' => $s->value,
                    'label' => ucfirst($s->value),
                ])
                ->values(),
        ]);
    }
}
