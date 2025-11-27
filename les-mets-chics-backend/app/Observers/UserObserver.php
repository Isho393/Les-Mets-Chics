<?php

namespace App\Observers;

use App\Events\UserActivated;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Vérifier si le statut a changé de 'pending' à 'active'
        if ($user->isDirty('status') && $user->status === 'active' && $user->getOriginal('status') === 'pending') {
            // Déclencher l'événement UserActivated
            event(new UserActivated($user));
        }
    }
}
