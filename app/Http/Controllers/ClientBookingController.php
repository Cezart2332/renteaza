<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Http\Requests\ClientBookCarRequest;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Str;

class ClientBookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $order  = $request->input('order', 'desc');

        $client = Auth::user();

        $query = $client->bookingsClient()
            ->with(['owner', 'vehicle']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                // caută după numele proprietarului
                $q->whereHas(
                    'owner',
                    fn($q2) =>
                    $q2->where('name', 'like', "%{$search}%")
                )
                    // sau după brand/model mașină
                    ->orWhereHas(
                        'vehicle',
                        fn($q2) =>
                        $q2->where('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%")
                    );
            });
        }

        $rows = $query
            ->orderBy('start_date', $order)
            ->paginate(5)
            ->withQueryString()
            ->through(fn($b) => [
                'id'     => $b->id,
                'owner'  => $b->owner->name,
                'car'    => "{$b->vehicle->brand} {$b->vehicle->model}",
                'start'  =>  $b->start_date->format('D, d M Y H:i'),
                'end'    => $b->end_date->format('D, d M Y H:i'),
                'status' => ucfirst($b->status),
                'details' => [
                    ['label' => 'Email proprietar',  'value' => $b->owner->email],
                    ['label' => 'Telefon proprietar', 'value' => $b->owner->phone],
                    ['label' => 'Preț/zi',          'value' => number_format($b->price_per_day, 2)],
                    ['label' => 'Total',            'value' => number_format($b->total_price, 2)],
                    ['label' => 'Garanție',         'value' => number_format($b->security_deposit, 2)],
                ],
            ]);

        return Inertia::render('Client/Bookings/Index', [
            'stats'      => $this->getStatusBookings($client->bookingsClient()),
            'rows'       => $rows,
            'prevSearch' => $search,
            'order'      => $order,
        ]);
    }

    public function show(Booking $booking)
    {
        // Make sure relations are loaded (avoids N+1 and null issues)
        $booking->load(['owner', 'vehicle', 'pickupLocation', 'rentalType']);

        return Inertia::render('Client/Bookings/Show', [
            'booking' => [
                // Core fields used by Show.vue
                'id'           => $booking->id,
                'status'       => (string) $booking->status, // leave as-is (lowercase/underscored)
                'total_price'  => (float) $booking->total_price,
                'currency'     => $booking->currency ?? 'RON',

                // Dates in ISO format so the FE can gate steps by day
                'start_at'     => optional($booking->start_date)->toIso8601String(),
                'end_at'       => optional($booking->end_date)->toIso8601String(),

                // Nice labels for UI
                'owner_name'   => optional($booking->owner)->name,
                'car_label'    => trim(($booking->vehicle->brand ?? '') . ' ' . ($booking->vehicle->model ?? '')),

                // Optional extras you might show in the detail section
                'owner_email'       => optional($booking->owner)->email,
                'owner_phone'       => optional($booking->owner)->phone,
                'price_per_day'     => (float) $booking->price_per_day,
                'security_deposit'  => (float) ($booking->security_deposit ?? 0),
                'pickup_name'       => optional($booking->pickupLocation)->name,
                'rental_type'       => optional($booking->rentalType)->name,
            ],
        ]);
    }

    protected function getStatusBookings($reservations): array
    {
        $now = now();
        $lastWeek = (clone $now)->subWeek();

        $current = [
            'Pending' => (clone $reservations)
                ->where('status', ReservationStatus::Pending->value)
                ->count(),
            'Cancelled' => (clone $reservations)
                ->where('status', ReservationStatus::Cancelled->value)
                ->count(),
            'Completed' => (clone $reservations)
                ->where('status', ReservationStatus::Completed->value)
                ->whereDate('end_date', '<', $now)
                ->count(),
        ];

        $previous = [
            'Pending' => (clone $reservations)
                ->where('status', ReservationStatus::Pending->value)
                ->whereDate('created_at', '<=', $lastWeek)
                ->count(),
            'Cancelled' => (clone $reservations)
                ->where('status', ReservationStatus::Cancelled->value)
                ->whereDate('updated_at', '<=', $lastWeek)
                ->count(),
            'Completed' => (clone $reservations)
                ->where('status', ReservationStatus::Completed->value)
                ->whereDate('end_date', '<', $lastWeek)
                ->count(),
        ];

        $stats = [];
        foreach ($current as $key => $curCount) {
            $prevCount = $previous[$key] ?? 0;
            $base = $prevCount > 0 ? $prevCount : 1;
            $delta = $curCount - $prevCount;
            $percent = round(($delta / $base) * 100, 2);
            $trend = $curCount >= $prevCount ? 'up' : 'down';

            $stats[$key] = [
                'title' => $key,
                'current' => $curCount,
                'previous' => $prevCount,
                'percent' => $percent,
                'trend' => $trend,
            ];
        }

        return $stats;
    }

    public function book(ClientBookCarRequest $request, Vehicle $vehicle)
    {
        $user = Auth::user();
        $owner = $vehicle->owner;

        $validated = $request->validated();

        // Convertim datele în obiecte Carbon
        $startDate = Carbon::parse($validated['pickupDate']);
        $endDate = Carbon::parse($validated['dropoffDate']);

        // Calculăm numărul total de zile (inclusiv ultima zi)
        $totalDays = $startDate->diffInDays($endDate) + 1;

        Booking::create([
            'id' => Str::uuid(),
            'client_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'owner_id' => $owner->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price_per_day' => $vehicle->price_per_day,
            'total_price' => $vehicle->price_per_day * $totalDays,
            'pickup_location_id' => $validated['pickupLocation'],
            'rental_type_id' => $validated['rentalType'],
        ]);

        return back()->with(
            'message',
            'Rezervarea a fost trimisă și urmează să fie aprobată de proprietar.'
        );
    }
}
