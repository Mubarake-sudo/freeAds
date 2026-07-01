<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;

class AdPolicy
{
    /**
     * Un utilisateur peut modifier uniquement ses propres annonces.
     */
    public function update(User $user, Ad $ad): bool
    {
        return $user->id === $ad->user_id;
    }

    /**
     * Un utilisateur peut supprimer uniquement ses propres annonces.
     */
    public function delete(User $user, Ad $ad): bool
    {
        return $user->id === $ad->user_id;
    }
}
