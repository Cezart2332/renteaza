<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        abort_unless(auth()->id() === $booking->client_id || (auth()->user()->is_admin ?? false), 403);

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        // $owner  = $booking->owner;

        // if (!$owner?->stripe_account_id) {
        //     // dacă userul curent ESTE proprietarul => du-l la onboarding
        //     if (auth()->id() === $booking->owner_id) {
        //         return redirect()
        //             ->route('user.payments.connect.start')
        //             ->with('warning', 'Conectează Stripe pentru a putea primi plăți.');
        //     }

        //     return back()->with('errorMessage', 'Plata nu este disponibilă momentan. Proprietarul nu și-a conectat încă Stripe.');
        // }

        // calculează comisionul (ex: 10%)
        $amount   = (int) round($booking->total_price * 100);
        $feePct   = (int) (config('payments.platform_fee_pct', 10)); // % din .env sau config
        $appFee   = (int) round($amount * $feePct / 100);

        if ($booking->stripe_payment_intent) {
            $intent = $stripe->paymentIntents->retrieve($booking->stripe_payment_intent);
        } else {
            $intent = $stripe->paymentIntents->create([
                'amount'   => $amount,
                'currency' => 'ron', // sau 'eur'
                'automatic_payment_methods' => ['enabled' => true],
                'transfer_group' => 'booking_' . $booking->id,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'owner_id'   => $booking->owner_id,
                    'client_id'  => $booking->client_id,
                ],
            ]);

            $booking->forceFill(['stripe_payment_intent' => $intent->id])->save();
        }

        return \Inertia\Inertia::render('Payments/Checkout', [
            'booking'        => ['id' => $booking->id, 'total' => $booking->total_price, 'status' => $booking->status],
            'publishableKey' => config('services.stripe.key'),
            'clientSecret'   => $intent->client_secret,
            'returnUrl'      => route('user.bookings.payment.success', $booking->id),
        ]);
    }

    public function success(Booking $booking)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $intent = $stripe->paymentIntents->retrieve($booking->stripe_payment_intent);

        if (!$intent) {
            return redirect()->route('user.bookings.index')
                ->with('warning', 'Nu am găsit plata.');
        }

        if ($intent->status === 'succeeded') {
            $amount = (int) $intent->amount_received;             // total în bani
            $feePct = (int) config('payments.platform_fee_pct', 10);
            $fee    = (int) round($amount * $feePct / 100);
            $net    = max(0, $amount - $fee);

            // Dacă n-am mai făcut transferul și avem cont conectat la proprietar
            if (!$booking->stripe_transfer_id && $booking->owner?->stripe_account_id && $net > 0) {
                $transfer = $stripe->transfers->create(
                    [
                        'amount'         => $net,
                        'currency'       => $intent->currency,
                        'destination'    => $booking->owner->stripe_account_id,
                        'transfer_group' => 'booking_' . $booking->id,
                    ],
                    [
                        // previne dubla-executare dacă userul dă refresh pe pagina de succes
                        'idempotency_key' => 'transfer_' . $booking->id,
                    ]
                );

                $booking->forceFill([
                    'stripe_transfer_id'   => $transfer->id,
                    'platform_fee_amount'  => $fee,
                    'status'               => ReservationStatus::Paid->value,
                    'payout_status'        => 'none',
                ])->save();
            } else {
                // fără cont conectat → marchează payout manual
                $booking->forceFill([
                    'platform_fee_amount'  => $fee,
                    'status'               => ReservationStatus::Paid->value,
                    'payout_status'        => $booking->owner?->stripe_account_id ? 'none' : 'manual_required',
                ])->save();
            }

            return redirect()->route('user.bookings.index')->with('success', 'Plata reușită.');
        }

        // alte stări
        if (in_array($intent->status, ['requires_payment_method', 'requires_confirmation', 'requires_action'])) {
            return redirect()->route('user.bookings.payment.show', $booking->id)
                ->with('errorMessage', 'Plata nu a fost confirmată.');
        }

        return redirect()->route('user.bookings.index')->with('errorMessage', 'Status plată: ' . $intent->status);
    }
}
