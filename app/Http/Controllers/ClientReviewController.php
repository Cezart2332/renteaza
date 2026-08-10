<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Http\Requests\ClientReviewStoreRequest;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ClientReviewController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $order  = $request->input('order', 'desc');
        $sortBy = $request->input('sortBy', 'reviewed_at');

        $allowedSorts = ['reviewed_at', 'rating'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'reviewed_at';
        }

        $client = Auth::user();

        $query = Review::query()
            ->where('reviewer_id', $client->id)
            ->with(['owner', 'vehicle']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'owner',
                    fn($q2) =>
                    $q2->where('name', 'like', "%{$search}%")
                )->orWhereHas(
                    'vehicle',
                    fn($q2) =>
                    $q2->where('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                );
            });
        }

        $rows = $query
            ->orderBy($sortBy, $order)
            ->paginate(10)
            ->withQueryString()
            ->through(function (Review $r) {
                return [
                    'id'           => $r->id,
                    'owner_name'  => $r->owner?->name ?? '-',
                    'car_name'     => $r->vehicle ? ($r->vehicle->brand . ' ' . $r->vehicle->model) : '-',
                    'rating'       => $r->rating,
                    'reviewed_at'  => optional($r->reviewed_at)->format('Y-m-d'),

                    'details' => [
                        ['label' => 'Titlu',               'value' => $r->title],
                        ['label' => 'Descriere',           'value' => $r->description],
                        ['label' => 'Email proprietar',    'value' => $r->owner?->email],
                        ['label' => 'Telefon proprietar',  'value' => $r->owner?->phone],
                    ],
                ];
            });

        // reviews for cars component
        $vehicleIdsFromBookings = Booking::query()
            ->where('client_id', $client->id)
            ->whereIn('status', [
                ReservationStatus::OwnerAccepted->value,
            ])
            ->pluck('vehicle_id')
            ->unique();

        $alreadyReviewedVehicleIds = Review::where('reviewer_id', $client->id)
            ->pluck('vehicle_id')
            ->unique();

        $unreviewedVehicles = Vehicle::query()
            ->whereIn('id', $vehicleIdsFromBookings)
            ->whereNotIn('id', $alreadyReviewedVehicleIds)
            ->with(['owner:id,name'])
            ->get(['id', 'brand', 'model', 'cover_image', 'owner_id', 'slug']);

        return Inertia::render('Client/Reviews/Index', [
            'unreviewedVehicles' => $unreviewedVehicles->map(function ($v) {
                return [
                    'id'     => $v->id,
                    'brand'  => $v->brand,
                    'model'  => $v->model,
                    'cover'  => $v->cover_image,
                    'owner'  => $v->owner?->name,
                    'slug'   => $v->slug,
                ];
            }),
            'reviews'    => $rows,
            'prevSearch' => $search,
        ]);
    }

    public function create($vehicleSlug)
    {
        $vehicle = Vehicle::where('slug', $vehicleSlug)->firstOrFail();

        return Inertia::render('Client/Reviews/Create', [
            'vehicle' => [
                'id'     => $vehicle->id,
                'brand'  => $vehicle->brand,
                'model'  => $vehicle->model,
                'cover'  => $vehicle->cover_image,
                'owner'  => $vehicle->owner?->name,
            ],
        ]);
    }

    public function store(ClientReviewStoreRequest $request)
    {
        $request->validated();

        $client = Auth::user();

        DB::transaction(function () use ($request, $client) {
            Review::create([
                'id'           => Str::uuid(),
                'reviewer_id'  => $client->id,
                'owner_id'     => Vehicle::findOrFail($request->vehicle_id)->owner_id,
                'vehicle_id'   => $request->vehicle_id,
                'rating'       => $request->rating,
                'title'        => $request->title,
                'description'  => $request->description,
                'reviewed_at'  => now(),
            ]);
        });

        return redirect()->route('user.client_reviews.index')
            ->with('message', 'Recenzia a fost adăugată cu succes.');
    }
}
