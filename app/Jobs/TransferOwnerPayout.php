<?php

namespace App\Jobs;

use App\Enums\ReservationStatus;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\StripeClient;

class TransferOwnerPayout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $bookingId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $booking = Booking::with(['owner'])->find($this->bookingId);
        if (!$booking) return;

        // dacă deja am transferul făcut sau nu e plătit, ieșim
        if ($booking->stripe_transfer_id || $booking->status !== ReservationStatus::Paid->value) return;

        $owner = $booking->owner;
        if (!$owner?->stripe_account_id) {
            // proprietarul nu e conectat -> marchez pentru payout manual
            $booking->update(['payout_status' => 'manual_required']);
            return;
        }

        $stripe = new StripeClient(config('services.stripe.secret'));
        $intent = $stripe->paymentIntents->retrieve($booking->stripe_payment_intent);

        if (!$intent || $intent->status !== 'succeeded') {
            // nimic de făcut (poți reîncerca mai târziu)
            $this->release(300); // reîncearcă în 5 min
            return;
        }

        // suma totală primitã (în bani)
        $amount = (int) $intent->amount_received;
        $fee    = (int) ($booking->platform_fee_amount ?? 0);
        $net    = max(0, $amount - $fee);

        if ($net <= 0) {
            $booking->update(['payout_status' => 'none']);
            return;
        }

        try {
            $transfer = $stripe->transfers->create(
                [
                    'amount'         => $net,
                    'currency'       => $intent->currency,
                    'destination'    => $owner->stripe_account_id,
                    'transfer_group' => 'booking_' . $booking->id,
                ],
                [
                    'idempotency_key' => 'transfer_' . $booking->id,
                ]
            );

            $booking->update([
                'stripe_transfer_id' => $transfer->id,
                'payout_status'      => 'none',
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // de ex. fonduri încă "pending" în balanță
            if (str_contains($e->getMessage(), 'Insufficient funds') || str_contains($e->getMessage(), 'balance')) {
                $this->release(600); // reîncearcă peste 10 min
                return;
            }
            // altă eroare fixă: marchează pt. intervenție
            $booking->update(['payout_status' => 'manual_required']);
        }
    }
}
