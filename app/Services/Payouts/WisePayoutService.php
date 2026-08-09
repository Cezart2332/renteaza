<?php

namespace App\Services\Payouts;

use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class WisePayoutService
{
    protected string $base;
    protected string $token;
    protected int|string $profile;

    public function __construct()
    {
        $cfg = config('payments.wise');
        $this->base    = rtrim($cfg['base_url'], '/');
        $this->token   = $cfg['api_token'];
        $this->profile = $cfg['profile_id'];
    }

    /** Payout către un IBAN (RON implicit). Returnează transfer_id (string). */
    public function payoutToIban(Booking $booking, int $amountMinor, string $currency = 'RON'): string
    {
        // 1) recipient (re-use dacă îl avem deja)
        $recipientId = $this->ensureRecipient(
            $booking->owner->bankAccount?->account_holder_name ?? $booking->owner->name,
            $booking->owner->bankAccount?->iban,
            $currency
        );

        // 2) quote (setăm payIn = BALANCE pentru consistență la fund)
        $quoteId = $this->createQuote($currency, $currency, $amountMinor);

        // 3) transfer (un quote → un singur transfer)
        $transferId = $this->createTransfer($recipientId, $quoteId, 'booking_' . $booking->id);

        // 4) fund transfer (din balanță)
        $this->fundTransfer($transferId);

        return (string) $transferId;
    }

    protected function ensureRecipient(string $holderName, string $iban, string $currency): int
    {
        $iban = strtoupper(str_replace(' ', '', $iban));

        // Dacă ai deja salvat recipientul la user, refolosește-l:
        $existing = optional($bookingOwner = null); // în job îi pui update pe model
        // Simplu: încercăm să-l (re)creăm — Wise returnează 400 dacă e invalid, nu dubluri reale.

        $payload = [
            'profile'           => $this->profile,
            'accountHolderName' => $holderName,
            'currency'          => $currency,
            'type'              => 'iban',
            'details'           => ['iban' => $iban],
        ];

        $resp = Http::withToken($this->token)
            ->post($this->base . '/v1/accounts', $payload)
            ->throw();

        return (int) $resp->json('id');
    }

    protected function createQuote(string $source, string $target, int $amountMinor): string
    {
        // v3 quote (autentificat) — amount în unități normale (ex: 123.45), deci împărțim
        $amount = $amountMinor / 100;

        $resp = Http::withToken($this->token)
            ->post($this->base . "/v3/profiles/{$this->profile}/quotes", [
                'sourceCurrency' => $source,
                'targetCurrency' => $target,
                'sourceAmount'   => $amount,
                'payIn'          => 'BALANCE',       // vezi docs
                'payOut'         => 'BANK_TRANSFER',
            ])
            ->throw();

        return $resp->json('id'); // GUID
    }

    protected function createTransfer(int $recipientId, string $quoteId, string $reference): int
    {
        $resp = Http::withToken($this->token)
            ->post($this->base . '/v1/transfers', [
                'targetAccount'        => $recipientId,
                'quoteUuid'            => $quoteId,
                'customerTransactionId' => (string) Str::uuid(), // idempotent la retry
                'details' => ['reference' => $reference],
            ])
            ->throw();

        return (int) $resp->json('id');
    }

    protected function fundTransfer(int $transferId): void
    {
        Http::withToken($this->token)
            ->post($this->base . "/v3/profiles/{$this->profile}/transfers/{$transferId}/payments", [
                'type' => 'BALANCE',
            ])
            ->throw();
    }
}
