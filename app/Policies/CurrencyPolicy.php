<?php

namespace App\Policies;

use App\Models\Currency;
use App\Models\User;

class CurrencyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view currencies
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Currency $currency): bool
    {
        return true; // All authenticated users can view any currency
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin users can create currencies
        // You may want to add a role check here
        return true; // For now, allowing all authenticated users
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Currency $currency): bool
    {
        // Only admin users can update currencies
        // You may want to add a role check here
        return true; // For now, allowing all authenticated users
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Currency $currency): bool
    {
        // Only admin users can delete currencies
        // You may want to add a role check here
        return true; // For now, allowing all authenticated users
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Currency $currency): bool
    {
        // Only admin users can restore currencies
        return true; // For now, allowing all authenticated users
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Currency $currency): bool
    {
        // Only admin users can force delete currencies
        return true; // For now, allowing all authenticated users
    }
}
