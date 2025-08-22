<?php

namespace App\Policies;

use App\Models\Investment;
use App\Models\User;

class InvestmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own investments
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Investment $investment): bool
    {
        return $user->id === $investment->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Authenticated users can create investments
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Investment $investment): bool
    {
        return $user->id === $investment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Investment $investment): bool
    {
        return $user->id === $investment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Investment $investment): bool
    {
        return $user->id === $investment->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Investment $investment): bool
    {
        return $user->id === $investment->user_id;
    }
}
