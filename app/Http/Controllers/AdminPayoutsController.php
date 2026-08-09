<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPayoutsController extends Controller
{
    public function index()
    {
        $rows = Booking::query()
            ->with(['owner:id,name', 'owner.bankAccount'])
            ->where('status', 'paid')
            ->where('payout_status', 'manual_required')
            ->orderByDesc('id')
            ->get()
            ->map(function ($b) {
                $grossMinor = (int) ($b->amount_paid ?? round($b->total_price * 100));
                $feePct     = (int) config('payments.platform_fee_pct', 10);
                $feeMinor   = is_null($b->platform_fee_amount)
                    ? (int) round($grossMinor * $feePct / 100)
                    : (int) $b->platform_fee_amount;
                $netMinor   = max(0, $grossMinor - $feeMinor);

                return [
                    'id'       => $b->id,
                    'owner'    => $b->owner?->name,
                    'iban'     => $b->owner?->bankAccount?->iban,
                    'currency' => $b->currency ?? 'RON',
                    'gross'    => $grossMinor,
                    'fee'      => $feeMinor,
                    'net'      => $netMinor,
                    'net_fmt'  => number_format($netMinor / 100, 2, '.', ''),
                    'payout_status' => $b->payout_status,
                ];
            });

        return Inertia::render('Admin/Payouts/Index', [
            'rows' => $rows,
        ]);
    }

    /** Export CSV (toate booking-urile paid + manual_required) */
    public function export(): StreamedResponse
    {
        $filename = 'payouts_' . now()->format('Ymd_His') . '.csv';

        $query = Booking::query()
            ->with(['owner:id,name', 'owner.bankAccount'])
            ->where('status', 'paid')
            ->where('payout_status', 'manual_required')
            ->orderBy('id');

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            // BOM pentru Excel
            fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($out, [
                'Booking ID',
                'Owner Name',
                'Account Holder',
                'IBAN',
                'Currency',
                'Gross (RON)',
                'Platform Fee (RON)',
                'Net (RON)',
                'Net (minor units)',
                'Reference',
                'Description',
                'Created At',
            ]);

            $query->chunk(500, function ($bookings) use ($out) {
                foreach ($bookings as $b) {
                    $grossMinor = (int) ($b->amount_paid ?? round($b->total_price * 100));
                    $feePct     = (int) config('payments.platform_fee_pct', 10);
                    $feeMinor   = is_null($b->platform_fee_amount)
                        ? (int) round($grossMinor * $feePct / 100)
                        : (int) $b->platform_fee_amount;
                    $netMinor   = max(0, $grossMinor - $feeMinor);

                    $currency   = $b->currency ?? 'RON';
                    $holderName = $b->owner?->bankAccount?->account_holder_name ?: $b->owner?->name;

                    fputcsv($out, [
                        $b->id,
                        $b->owner?->name,
                        $holderName,
                        $b->owner?->bankAccount?->iban,
                        $currency,
                        number_format($grossMinor / 100, 2, '.', ''),
                        number_format($feeMinor   / 100, 2, '.', ''),
                        number_format($netMinor   / 100, 2, '.', ''),
                        $netMinor,                                 // minor units (bani)
                        'booking_' . $b->id,                       // reference
                        'Payout booking #' . $b->id,               // description
                        optional($b->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type'  => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /** Marchează payout-ul ca plătit (după OP/transfer manual) */
    public function markPaid(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'payout_reference' => ['required', 'string', 'max:191'], // ex.: Nr. OP / referință bancă
        ]);

        $booking->update([
            'payout_status'    => 'none',
            'payout_reference' => $data['payout_reference'],
            'paid_out_at'      => now(),
        ]);

        return back()->with('success', 'Payout marcat ca efectuat.');
    }
}
