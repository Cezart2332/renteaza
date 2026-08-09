<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Contracts\StartContractAction;
use App\Http\Controllers\Controller;
use App\Models\CheckinSubmission;
use App\Enums\ReservationStatus;
use App\Notifications\CheckInApprovedNotification;
use App\Notifications\CheckInRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCheckinReviewController extends Controller
{
    // Va returna o pagină Inertia cu pending submissions (pasul următor)
    public function index()
    {
        $submissions = CheckinSubmission::query()
            ->with([
                'booking:id,client_id,vehicle_id,status',
                'booking.client:id,name',          // ajustează relația dacă ai alt nume
                'photos' => fn($q) => $q->orderBy('position'),
            ])
            ->where('status', 'pending')
            ->latest()
            ->paginate(12)
            ->through(function ($s) {
                return [
                    'id'          => $s->id,
                    'submitted_at' => $s->created_at->toDateTimeString(),
                    'booking'     => [
                        'id'     => $s->booking->id,
                        'status' => $s->booking->status,
                        'client' => $s->booking->client?->name,
                    ],
                    'photos'      => $s->photos->map(fn($p) => [
                        'position' => $p->position,
                        'url'      => $p->url, // vine din accessor
                    ]),
                ];
            });

        return inertia('Admin/Checkins/Index', [
            'submissions' => $submissions,
        ]);
    }

    public function approve(CheckinSubmission $submission)
    {
        abort_if($submission->status !== 'pending', 409, 'Submission already reviewed.');

        DB::transaction(function () use ($submission) {
            $submission->update(['status' => 'approved']);
            $booking = $submission->booking()->lockForUpdate()->first();
            $booking->update(['status' => ReservationStatus::CheckInApproved->value]);

            app(StartContractAction::class)->execute($booking, auth()->id());

            // notificări (null-safe; presupunem relațiile booking->owner, booking->client)
            $booking->owner?->notify(new CheckInApprovedNotification($booking->id, $submission->id));
            $booking->client?->notify(new CheckInApprovedNotification($booking->id, $submission->id));
        });

        return back()->with('success', 'Check-in aprobat. Contractul a fost generat.');
    }

    public function reject(CheckinSubmission $submission, Request $request)
    {
        abort_if($submission->status !== 'pending', 409, 'Submission already reviewed.');
        $data = $request->validate(['reason' => ['required', 'string', 'max:1000']]);

        DB::transaction(function () use ($submission, $data) {
            $submission->update(['status' => 'rejected', 'notes' => $data['reason']]);
            $booking = $submission->booking()->lockForUpdate()->first();
            $booking->update(['status' => ReservationStatus::CheckInRejected->value]);

            $booking->owner?->notify(new CheckInRejectedNotification($booking->id, $submission->id, $data['reason']));
            $booking->client?->notify(new CheckInRejectedNotification($booking->id, $submission->id, $data['reason']));
        });

        return back()->with('success', 'Check-in respins.');
    }
}
