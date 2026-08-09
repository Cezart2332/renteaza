<?php

namespace App\Http\Controllers\Owner;

use App\Actions\Bookings\SubmitCheckInPhotosAction;
use App\Enums\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitCheckInPhotosRequest;
use App\Models\Booking;
use App\Models\CalendarDayOverride;
use App\Models\CheckinSubmission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OwnerBookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $order = $request->input('order', 'desc');

        $owner = Auth::user();

        $query = $owner->bookingsOwner()
            ->with(['client', 'vehicle']);
        // ->whereIn('status', [
        //     ReservationStatus::Completed->value,
        //     ReservationStatus::Cancelled->value,
        // ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                // filter by client name
                $q->whereHas(
                    'client',
                    fn($q2) =>
                    $q2->where('name', 'like', "%{$search}%")
                )
                    // OR filter by car brand/model
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
            ->through(function ($b) {
                $base = (float) $b->vehicle->price_per_day;

                // 1) Zilele tarifate (porțiune de zi = 1 zi; dacă end e 00:00, nu numărăm ultima zi)
                $start = $b->start_date->copy();
                $end = $b->end_date->copy();
                $startDay = $start->copy()->startOfDay();
                $endDay = $end->copy()->startOfDay();
                $endIsMidnight = $end->isStartOfDay();

                $dayDiff = $startDay->diffInDays($endDay);
                $daysCount = max(1, $dayDiff + ($endIsMidnight ? 0 : 1));

                $dates = [];
                for ($i = 0; $i < $daysCount; $i++) {
                    $dates[] = $startDay->copy()->addDays($i)->toDateString(); // 'YYYY-MM-DD'
                }

                // 2) Override-uri din calendar pentru acele zile (cheie: 'YYYY-MM-DD')
                $overrides = CalendarDayOverride::query()
                    ->where('vehicle_id', $b->vehicle_id)
                    ->whereIn('date', $dates)
                    ->whereNotNull('custom_price')
                    ->get()
                    ->keyBy(fn($o) => $o->date->toDateString());

                // 3) Construim breakdown + aflăm dacă e variabil
                $perDay = [];
                $allEqualBase = true;
                foreach ($dates as $d) {
                    $ov = $overrides->get($d);
                    $custom = $ov?->custom_price;
                    $price = $custom !== null ? (float) $custom : $base;

                    if ($price !== $base)
                        $allEqualBase = false;

                    $perDay[] = [
                        'date' => $d,
                        'label' => Carbon::parse($d)->translatedFormat('D, d M'),
                        'price' => $price,
                        'source' => $custom !== null ? 'calendar' : 'vehicle',
                    ];
                }

                $isVariable = $overrides->isNotEmpty() || !$allEqualBase;

                $pricePerDayLabel = $isVariable ? 'Preț/zi (calendar)' : 'Preț/zi (vehicul)';
                $pricePerDayValue = $isVariable ? 'variabil' : number_format($base, 2);

                // 4) Detalii + breakdown pe zile doar când e variabil
                $details = [
                    ['label' => 'Email client', 'value' => $b->client->email],
                    ['label' => 'Telefon client', 'value' => $b->client->phone],
                    ['label' => $pricePerDayLabel, 'value' => $pricePerDayValue],
                    ['label' => 'Preț total', 'value' => number_format($b->total_price, 2)],
                    ['label' => 'Depozit de garanție', 'value' => number_format($b->security_deposit, 2)],
                ];

                if ($isVariable) {
                    foreach ($perDay as $pd) {
                        $tag = $pd['source'] === 'calendar' ? ' (custom)' : '';
                        $details[] = [
                            'label' => '— ' . $pd['label'],
                            'value' => number_format($pd['price'], 2) . ' RON' . $tag,
                        ];
                    }
                }

                return [
                    'id' => $b->id,
                    'client' => $b->client->name,
                    'car' => "{$b->vehicle->brand} {$b->vehicle->model}",
                    'start' => $b->start_date->format('D, d M Y H:i'),
                    'end' => $b->end_date->format('D, d M Y H:i'),
                    'status' => ucfirst($b->status),
                    'details' => $details,

                    // util pentru badge în UI (ex. „calendar” vs „vehicul”)
                    'price_source' => $isVariable ? 'calendar' : 'vehicle',
                    // dacă vrei și array-ul brut pentru un tabel separat:
                    // 'per_day_breakdown' => $perDay,
                ];
            });

        return Inertia::render('Owner/Bookings/Index', [
            'stats' => $this->getStatusBookings($owner->bookingsOwner()),
            'rows' => $rows,
            'prevSearch' => $search
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

    public function upcomingBookings()
    {

        $owner = Auth::user();
        $reservations = $owner->bookingsOwner();

        $rawUpcoming = (clone $reservations)
            ->where('status', ReservationStatus::Pending)
            ->with(['client', 'vehicle'])
            ->orderBy('start_date', 'asc')
            ->get();


        $upcomingList = $rawUpcoming->map(fn($b) => [
            'id' => $b->id,
            'client' => [
                'photo' => $b->client->photo_url,
                'name' => $b->client->name,
                'location' => $b->client->location,
                'created_at' => $b->client->created_at->format('Y-m-d'),
                'reservations_count' => $b->client->bookingsClient()->count(),
                'rating' => $b->client->rating,
            ],
            'car' => [
                'brand' => $b->vehicle->brand,
                'model' => $b->vehicle->model,
                'year' => $b->vehicle->year,
            ],
            'details' => [
                ['label' => 'Email Client', 'value' => $b->client->email, 'is_sensitive' => true],
                ['label' => 'Telefon Client', 'value' => $b->client->phone, 'is_sensitive' => true],
                ['label' => 'Preț per zi', 'value' => number_format($b->price_per_day, 2)],
                ['label' => 'Preț total', 'value' => number_format($b->total_price, 2)],
                ['label' => 'Depozit de garanție', 'value' => number_format($b->security_deposit, 2)],
            ],
            'start_date' => Carbon::parse($b->start_date)->format('Y-m-d H:i'),
            'end_date' => Carbon::parse($b->end_date)->format('Y-m-d H:i'),
            'days' => $b->start_date->copy()->startOfDay()->diffInDays($b->end_date->copy()->startOfDay()) + 1,

        ]);
        return Inertia::render('Owner/Bookings/UpcomingBookings', [
            'upcomingList' => $upcomingList,
        ]);
    }

    public function checkIn(string $bookingId)
    {
        $booking = Booking::find($bookingId);
        return Inertia::render('Owner/Bookings/CheckIn', [
            'booking' => [
                'id' => $booking->id,
                'client' => [
                    'photo' => $booking->client->photo_url,
                    'name' => $booking->client->name,
                    'location' => $booking->client->location,
                    'created_at' => $booking->client->created_at->format('Y-m-d'),
                    'reservations_count' => $booking->client->bookingsClient()->count(),
                    'rating' => $booking->client->rating,
                ],
                'car' => [
                    'brand' => $booking->vehicle->brand,
                    'model' => $booking->vehicle->model,
                    'year' => $booking->vehicle->year,
                ],
                'details' => [
                    ['label' => 'Email Client', 'value' => $booking->client->email, 'is_sensitive' => true],
                    ['label' => 'Telefon Client', 'value' => $booking->client->phone, 'is_sensitive' => true],
                    ['label' => 'Preț per zi', 'value' => number_format($booking->price_per_day, 2)],
                    ['label' => 'Preț total', 'value' => number_format($booking->total_price, 2)],
                    ['label' => 'Depozit de garanție', 'value' => number_format($booking->security_deposit, 2)],
                ],
                'start_date' => Carbon::parse($booking->start_date)->format('Y-m-d H:i'),
                'end_date' => Carbon::parse($booking->end_date)->format('Y-m-d H:i'),
                'days' => $booking->start_date->copy()->startOfDay()->diffInDays($booking->end_date->copy()->startOfDay()) + 1,

            ]
        ]);
    }

    public function checkOut(Booking $booking)
    {
        // ultimul check-in aprobat (cu poze)
        $lastApproved = CheckinSubmission::query()
            ->where('booking_id', $booking->id)
            ->where('status', 'approved')
            ->latest()
            ->with('photos')
            ->first();

        $checkinPhotos = $lastApproved
            ? $lastApproved->photos->sortBy('id')->values()->map(fn($p) => Storage::url($p->path))->all()
            : [];

        return Inertia::render('Owner/Bookings/CheckOut', [
            'booking'       => [
                'id'         => $booking->id,
                'start_date' => optional($booking->start_date)->format('Y-m-d H:i'),
                'end_date'   => optional($booking->end_date)->format('Y-m-d H:i'),
                'days'       => $booking->start_date?->copy()->startOfDay()->diffInDays($booking->end_date?->copy()->startOfDay()) + 1,
                'status'     => $booking->status,
            ],
            'checkinPhotos' => $checkinPhotos,               // max 4, în ordinea stocată
            'canSubmit'     => now()->greaterThanOrEqualTo($booking->end_date), // disponibil după end_date
        ]);
    }

    public function approve(string $bookingId)
    {
        $booking = Booking::find($bookingId);
        $booking->update([
            'status' => ReservationStatus::OwnerAccepted->value,
        ]);

        return redirect()->route('user.bookings.checkin', $bookingId);
    }

    public function reject(string $bookingId)
    {
        $booking = Booking::find($bookingId);
        $booking->update([
            'status' => ReservationStatus::Cancelled->value,
        ]);

        return back()->with('message', 'Rezervarea a fost respinsă.');
    }

    public function storeCheckInPhotos(SubmitCheckInPhotosRequest $request, Booking $booking)
    {
        // (în pasul următor mutăm verificările de rol/status în authorize() și/sau Policy)
        $photos = $request->file('photos');

        app(SubmitCheckInPhotosAction::class)->execute(
            booking: $booking,
            photos: $photos,
            userId: auth()->id(),
            type: 'checkin'
        );

        return back()->with('success', 'Fotografiile au fost trimise spre verificare.');
    }

    public function storeCheckOutPhotos(SubmitCheckInPhotosRequest $request, Booking $booking)
    {
        // (în pasul următor mutăm verificările de rol/status în authorize() și/sau Policy)
        $photos = $request->file('photos');

        app(SubmitCheckInPhotosAction::class)->execute(
            booking: $booking,
            photos: $photos,
            userId: auth()->id(),
            type: 'checkout'
        );

        return back()->with('success', 'Fotografiile au fost trimise spre verificare.');
    }
}
