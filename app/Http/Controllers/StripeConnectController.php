<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\StripeClient;

class StripeConnectController extends Controller
{
    public function start()
    {
        $user = auth()->user();
        $stripe = new StripeClient(config('services.stripe.secret'));

        // creează cont Express dacă lipsește
        if (!$user->stripe_account_id) {
            $account = $stripe->accounts->create([
                'type' => 'express',
                'country' => 'RO', // adaptează dacă e altă țară
                'email' => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers'     => ['requested' => true],
                ],
            ]);
            $user->forceFill(['stripe_account_id' => $account->id])->save();
        } else {
            $account = $stripe->accounts->retrieve($user->stripe_account_id);
        }

        // link de onboarding/refresh
        $link = $stripe->accountLinks->create([
            'account'     => $account->id,
            'refresh_url' => route('user.payments.connect.refresh'),
            'return_url'  => route('user.payments.connect.return'),
            'type'        => 'account_onboarding',
        ]);

        return Inertia::location($link->url);
    }

    public function return()
    {
        return back()->with('success', 'Contul Stripe este conectat (test).');
    }

    public function refresh()
    {
        return redirect()->route('user.payments.connect.start');
    }
}
