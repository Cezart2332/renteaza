<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Models\Booking;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ContractController extends Controller
{
    public function show(Booking $booking)
    {
        $contract = Contract::with(['signers.user'])->where('booking_id', $booking->id)->firstOrFail();
        $me = auth()->user();
        
        $viewerRole =
            $me->id === $booking->owner_id  ? 'owner'  : ($me->id === $booking->client_id ? 'client' : 'admin');

        $mySigner = $contract->signers->firstWhere('user_id', $me->id);
        $canSign = $mySigner && !$mySigner->has_signed
            && in_array($booking->status, ['contract_pending', 'contract_partially_signed'], true);

        return Inertia::render('Contracts/Show', [
            'booking'     => ['id' => $booking->id, 'status' => $booking->status],
            'contract'    => ['id' => $contract->id, 'status' => $contract->status, 'document' => $contract->document_path],
            'signers'     => $contract->signers->map(fn($s) => [
                'id' => $s->id,
                'role' => $s->role,
                'name' => $s->name_snapshot ?? $s->user?->name,
                'email' => $s->email_snapshot ?? $s->user?->email,
                'has_signed' => $s->has_signed,
                'signed_at' => optional($s->signed_at)?->toDateTimeString(),
            ]),
            'viewer_role' => $viewerRole,      // <— client/owner/admin
            'canSign'     => $canSign,         // <— controlează butonul
            'my_signer_id' => $mySigner?->id,   // <— pentru info/UX
        ]);
    }

    public function sign(Booking $booking)
    {
        $userId = auth()->id();

        DB::transaction(function () use ($booking, $userId) {
            $contract = Contract::with('signers')->where('booking_id', $booking->id)->lockForUpdate()->firstOrFail();

            $signer = $contract->signers->firstWhere('user_id', $userId);
            abort_unless($signer && !$signer->has_signed, 409, 'Nu poți semna.');

            $signer->update(['has_signed' => true, 'signed_at' => now()]);

            $signed = $contract->signers->where('has_signed', true)->count();
            if ($signed === $contract->signers->count()) {
                $contract->update(['status' => 'signed']);
                $booking->update(['status' => ReservationStatus::PaymentPending->value]);
            } else {
                $contract->update(['status' => 'partially_signed']);
                $booking->update(['status' => 'contract_partially_signed']);
            }
        });

        return back()->with('success', 'Contract semnat cu succes.');
    }
}
