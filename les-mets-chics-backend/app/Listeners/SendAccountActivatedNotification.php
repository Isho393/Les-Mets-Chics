<?php

namespace App\Listeners;

use App\Events\UserActivated;
use App\Mail\AccountActivated;
use Illuminate\Support\Facades\Mail;

class SendAccountActivatedNotification
{
    public function handle(UserActivated $event): void
    {
        // Envoyer l'email à l'utilisateur
        Mail::to($event->user->email)->send(new AccountActivated($event->user));
    }
}
