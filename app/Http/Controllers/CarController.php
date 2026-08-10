<?php

namespace App\Http\Controllers;

use App\Enums\CarType;
use App\Enums\ReservationStatus;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Location;
use App\Models\RentalType;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['rentType', 'pickupLocation', 'pickupDate', 'dropoffDate', 'carType']);

        $pickupDate = $filters['pickupDate'] ?? null;
        $dropoffDate = $filters['dropoffDate'] ?? null;

        $cars = Vehicle::query()
            // Filter by car type
            ->when($filters['carType'] ?? null, function ($query, $carType) {
                $query->where('car_type', $carType);
            })

            // Filter by rental type via pivot
            ->when($filters['rentType'] ?? null, function ($query, $rentTypeId) {
                $query->whereHas('rentalTypes', function ($q) use ($rentTypeId) {
                    $q->where('rental_type_id', $rentTypeId);
                });
            })

            // Filter by pickup location via pivot
            ->when($filters['pickupLocation'] ?? null, function ($query, $locationId) {
                $query->whereHas('locations', function ($q) use ($locationId) {
                    $q->where('location_id', $locationId);
                });
            })

            // Exclude vehicles that are already booked in that period
            ->when($pickupDate && $dropoffDate, function ($query) use ($pickupDate, $dropoffDate) {
                $query->whereDoesntHave('bookings', function ($q) use ($pickupDate, $dropoffDate) {
                    $q->where(function ($subQ) use ($pickupDate, $dropoffDate) {
                        $subQ->whereBetween('start_date', [$pickupDate, $dropoffDate])
                            ->orWhereBetween('end_date', [$pickupDate, $dropoffDate])
                            ->orWhere(function ($innerQ) use ($pickupDate, $dropoffDate) {
                                $innerQ->where('start_date', '<=', $pickupDate)
                                    ->where('end_date', '>=', $dropoffDate);
                            });
                    });
                });
            })
            ->withCount(['reviews as reviews_nr'])
            ->withAvg('reviews as average_rating', 'rating')
            ->with(['locations', 'rentalTypes', 'fuelType', 'transmission', 'company'])
            ->paginate(5);

        return Inertia::render('Car/IndexListSidebar', [
            'cars' => $cars,
            'rentalTypes' => RentalType::select('id', 'label')->get(),
            'locations' => Location::select('id', 'name')->get(),
            'carTypes' => CarType::values(),
            'filters' => $filters,
        ]);
    }

    public function show($slug)
    {
        $vehicle = Vehicle::where('slug', $slug)->with(['locations', 'rentalTypes', 'fuelType', 'transmission'])->firstOrFail();

        // Luăm doar intervalele care trebuie blocate în calendar
        $bookedRanges = Booking::query()
            ->where('vehicle_id', $vehicle->id)
            ->whereIn('status', [
                ReservationStatus::OwnerAccepted->value,
                ReservationStatus::Pending->value,
            ])
            ->get(['start_date', 'end_date'])
            ->map(fn($b) => [
                'from' => Carbon::parse($b->start_date)->toDateString(),
                'to' => Carbon::parse($b->end_date)->toDateString(),
            ]);

        $reviews_nr = $vehicle->reviews()->count();
        $averageRating = round($vehicle->reviews->avg('rating'), 1);

        return Inertia::render('Car/Show', [
            'vehicle' => $vehicle,
            'similarVehicles' => Vehicle::where('id', '!=', $vehicle->id)
                ->with(['locations', 'rentalTypes', 'fuelType', 'transmission'])
                ->withCount(['reviews as reviews_nr'])
                ->withAvg('reviews as average_rating', 'rating')
                ->take(3)
                ->get(),
            'pickupLocations' => $vehicle->locations,
            'bookedRanges' => $bookedRanges,
            'reviews_nr' => $reviews_nr,
            'average_rating' => $averageRating,
            'rentalTypes' => $vehicle->rentalTypes
        ]);
    }
}
