<?php

namespace App\Actions\Contracts;

use App\Enums\ReservationStatus;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\ContractSigner;

class StartContractAction
{
    public function execute(Booking $booking, ?int $initiatorUserId = null): Contract
    {
        // 1) Contract (sau ia-l pe cel existent)
        $contract = Contract::firstOrCreate(
            ['booking_id' => $booking->id],
            ['status' => 'pending', 'created_by' => $initiatorUserId]
        );

        // 2) Semnatari (owner + client) — creează doar dacă lipsesc
        $this->ensureSigner($contract, $booking->owner_id, 'owner', $booking->owner?->name, $booking->owner?->email, $booking->owner?->phone);
        $this->ensureSigner($contract, $booking->client_id, 'client', $booking->client?->name, $booking->client?->email, $booking->client?->phone);

        // 3) Mută flow-ul în contract_pending
        if (
            $booking->status !== ReservationStatus::ContractPending->value
            && $booking->status !== ReservationStatus::ContractPartiallySigned->value
            && $booking->status !== ReservationStatus::ContractSigned->value
        ) {
            $booking->update(['status' => ReservationStatus::ContractPending->value]);
        }

        return $contract;
    }

    protected function ensureSigner(Contract $contract, ?int $userId, string $role, ?string $name, ?string $email, ?string $phone): void
    {
        if (!$userId) return;

        ContractSigner::firstOrCreate(
            ['contract_id' => $contract->id, 'role' => $role],
            [
                'user_id'        => $userId,
                'name_snapshot'  => $name,
                'email_snapshot' => $email,
                'phone_snapshot' => $phone,
                'has_signed'     => false,
            ]
        );
    }
}
