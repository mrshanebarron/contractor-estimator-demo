<?php

namespace App\Policies;

use App\Models\Estimate;
use App\Models\User;

class EstimatePolicy
{
    public function view(User $user, Estimate $estimate): bool
    {
        return $user->id === $estimate->user_id;
    }

    public function update(User $user, Estimate $estimate): bool
    {
        return $user->id === $estimate->user_id;
    }

    public function delete(User $user, Estimate $estimate): bool
    {
        return $user->id === $estimate->user_id;
    }
}
