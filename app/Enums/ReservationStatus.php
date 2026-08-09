<?php

namespace App\Enums;

enum ReservationStatus: string
{
    // Booking inițial
    case Pending = 'pending';                    // creat de client

        // Check-in (owner -> admin)
    case OwnerAccepted = 'owner_accepted';       // owner a confirmat și pornește check-in-ul
    case CheckInSubmitted = 'checkin_submitted'; // owner a încărcat pozele
    case CheckInApproved = 'checkin_approved';   // admin a aprobat check-in-ul
    case CheckInRejected = 'checkin_rejected';   // admin a respins check-in-ul (poze reupload)

        // Contract & plată
    case ContractPending = 'contract_pending';
    case ContractPartiallySigned = 'contract_partially_signed';
    case ContractSigned = 'contract_signed';
    case PaymentPending = 'payment_pending';
    case Paid = 'paid';

        // Check-out (client/owner -> admin)
    case CheckOutSubmitted = 'checkout_submitted';
    case CheckOutApproved = 'checkout_approved';
    case CheckOutRejected = 'checkout_rejected';

        // Finalizare / ramuri generale
    case Completed = 'completed';
    case Rejected = 'rejected';                  // rezervare respinsă (înainte de check-in)
    case Cancelled = 'cancelled';                // anulată de client/owner
    case Disputed = 'disputed';                  // opțional, dacă apar litigii

    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
