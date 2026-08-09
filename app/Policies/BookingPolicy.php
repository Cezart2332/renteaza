<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return false;
    }

    public function viewContract(User $user, Booking $booking): bool
    {
        return $user->id === $booking->owner_id
            || $user->id === $booking->client_id
            || ($user->is_admin ?? false); // adaptează la cum marchezi adminii
    }

    /**
     * Poate semna dacă e semnatarul lui și încă nu a semnat.
     */
    public function signContract(User $user, Booking $booking): bool
    {
        $contract = $booking->contract; // relație hasOne Contract
        if (!$contract) return false;

        return $contract->signers()
            ->where('user_id', $user->id)
            ->where('has_signed', false)
            ->exists();
    }
}
