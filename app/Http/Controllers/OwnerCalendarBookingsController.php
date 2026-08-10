<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Models\Booking;
use App\Models\CalendarDayOverride;
use App\Models\Vehicle;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OwnerCalendarBookingsController extends Controller
{
    public function show($slug)
    {
        $vehicle = Vehicle::where('slug', $slug)->with(['locations', 'rentalTypes', 'fuelType', 'transmission'])->firstOrFail();

        // Luăm doar intervalele care trebuie blocate în calendar
        $bookedRanges = Booking::query()
            ->where('vehicle_id', $vehicle->id)
            ->whereIn('status', [
                ReservationStatus::OwnerAccepted->value,
            ])
            ->get(['start_date', 'end_date'])
            ->map(fn($b) => [
                'from' => Carbon::parse($b->start_date)->toDateString(),
                'to' => Carbon::parse($b->end_date)->toDateString(),
            ]);

        //Override-uri de preț pe zile
        $dayOverrides = CalendarDayOverride::query()
            ->where('vehicle_id', $vehicle->id)
            ->whereNotNull('custom_price')
            ->get(['date', 'custom_price'])
            ->mapWithKeys(fn($row) => [
                Carbon::parse($row->date)->toDateString() => (float) $row->custom_price
            ]);


        $blockedDates = CalendarDayOverride::query()
            ->where('vehicle_id', $vehicle->id)
            ->where('is_blocked', true)
            ->get(['date'])
            ->map(fn($row) => Carbon::parse($row->date)->toDateString());


        //rezervari details
        $rawReservations = Booking::query()
            ->where('vehicle_id', $vehicle->id)
            ->whereIn('status', [
                ReservationStatus::OwnerAccepted->value,
            ])
            ->with('client:id,name')
            ->orderBy('start_date')
            ->get(['id', 'client_id', 'start_date', 'end_date']);

        $reservations = $rawReservations->map(function ($b) {
            $start = $b->start_date->copy()->startOfDay();
            $end = $b->end_date->copy()->startOfDay();

            return [
                'id' => $b->id,
                'guest_name' => optional($b->client)->name ?? 'Client',
                'start_at' => $b->start_date->toIso8601String(),
                'end_at' => $b->end_date->toIso8601String(),
                'days' => $start->diffInDays($end) + 1, // + ziua de checkout
            ];
        });


        return Inertia::render('Owner/Calendars/Show', [
            'vehicle' => $vehicle,
            'bookedRanges' => $bookedRanges,
            'priceByDate' => $dayOverrides,
            'blockedDates' => $blockedDates,
            'reservations' => $reservations,
        ]);
    }

    public function getPrice(Request $request, $vehicleSlug)
    {
        $vehicle = Vehicle::where('slug', $vehicleSlug)->firstOrFail();

        $start = $request->query('start');
        $end = $request->query('end');

        $basePrice = (float) $vehicle->price_per_day;
        $currency = $vehicle->currency ?? 'RON';
        $startDate = Carbon::parse($start)->toDateString();
        $endDate = Carbon::parse($end)->toDateString();

        // dacă avem interval (start + end)
        if ($start && $end) {
            // dacă e doar o singură zi
            $startC = Carbon::parse($start);
            $endC = Carbon::parse($end);

            if ($startC->isSameDay($endC)) {

                $override = CalendarDayOverride::where('vehicle_id', $vehicle->id)
                    ->where('date', $startDate)
                    ->value('custom_price');

                $price = $override ?? $basePrice;

                return Inertia::render('Owner/Calendars/PriceSmartModal', [
                    'price' => $price,
                    'currency' => $currency,
                    'dateLabel' => Carbon::parse($start)->isoFormat('D MMMM YYYY'),
                    'vehicleSlug' => $vehicle->slug,
                    'start' => $startDate,
                    'end' => $endDate
                ]);
            }

            $overrides = CalendarDayOverride::where('vehicle_id', $vehicle->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->pluck('custom_price', 'date');

            $prices = [];

            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $day) {
                $key = $day->toDateString();
                $prices[] = $overrides[$key] ?? $basePrice;
            }

            return Inertia::render('Owner/Calendars/PriceSmartModal', [
                'min_price' => min($prices),
                'max_price' => max($prices),
                'currency' => $currency,
                'rangeLabel' => Carbon::parse($startDate)->format('d M') . ' – ' . Carbon::parse($endDate)->format('d M'),
                'vehicleSlug' => $vehicle->slug,
                'start' => $startDate,
                'end' => $endDate
            ]);
        }


        // fallback: fără interval sau dată
        return Inertia::render('Owner/Calendars/PriceSmartModal', [
            'price' => $basePrice,
            'currency' => $currency,
            'vehicleSlug' => $vehicle->slug,
            'start' => $startDate,
            'end' => $endDate
        ]);
    }


    public function setPrice(Request $request, $vehicleSlug)
    {
        $data = $request->validate([
            'start' => ['required'],
            'end' => ['required', 'after_or_equal:start'],
            'smart' => ['required', 'boolean'],
            'custom_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $vehicle = Vehicle::where('slug', $vehicleSlug)->firstOrFail();

        $from = Carbon::parse($data['start'])->startOfDay();
        $to = Carbon::parse($data['end'])->startOfDay();

        // Iterăm zilele din interval (inclusiv capetele)
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $dateStr = $d->toDateString();

            if ($data['smart']) {
                // SET custom price pentru ziua respectivă
                CalendarDayOverride::updateOrCreate(
                    ['vehicle_id' => $vehicle->id, 'date' => $dateStr],
                    [
                        'custom_price' => $data['custom_price'],
                    ]
                );
            } else {
                // UNSET custom price → revine la base price
                CalendarDayOverride::where('vehicle_id', $vehicle->id)
                    ->where('date', $dateStr)
                    ->update(['custom_price' => null]);
            }
        }

        return redirect()->route('user.calendar.show', ['vehicleSlug' => $vehicleSlug])
            ->with('message', 'Prețul a fost actualizat cu succes.');
    }

    public function setAvailability(Request $request, $vehicleSlug)
    {
        $vehicle = Vehicle::where('slug', $vehicleSlug)->firstOrFail();

        $start = $request->input('start');
        $end = $request->input('end', $start);
        $isBlocked = (bool) $request->input('is_blocked');

        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $day) {
            CalendarDayOverride::updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'date' => $day->toDateString()],
                ['is_blocked' => $isBlocked]
            );
        }

        return redirect()->route('user.calendar.show', ['vehicleSlug' => $vehicleSlug])
            ->with('message', 'Disponibilitatea a fost actualizată.');
    }

    public function showBooking($vehicleSlug, $bookingId)
    {
        $vehicle = Vehicle::where('slug', $vehicleSlug)->firstOrFail();
        $booking = Booking::with('client', 'vehicle', 'rentalType', 'pickupLocation')->findOrFail($bookingId);
        $start = $booking->start_date->copy()->startOfDay();
        $end = $booking->end_date->copy()->startOfDay();

        // sa adaug si numarul de zile dar nu exista booking_days
        return Inertia::render('Owner/Calendars/ShowBooking', [
            'vehicle' => $vehicle,
            'booking' => $booking,
            'days' => $start->diffInDays($end) + 1,
        ]);
    }
}
