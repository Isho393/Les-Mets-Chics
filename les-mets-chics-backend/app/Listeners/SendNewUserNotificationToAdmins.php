<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\NewUserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendNewUserNotificationToAdmins
{
    public function handle(UserRegistered $event): void
    {
        // Récupérer tous les admins
        $admins = User::where('role', 'admin')->get();

        // Envoyer l'email à chaque admin
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewUserRegistered($event->user));
        }
    }
}
