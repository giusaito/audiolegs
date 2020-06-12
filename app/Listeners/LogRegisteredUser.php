<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Laravue\Models\Activity;

class LogRegisteredUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $subject = "Usuário registrado";
        $description = $event->user->email . ' registrou-se no sistema';

       $activity = activity($subject)
           ->causedBy($event->user->id)
           ->performedOn($event->user)
           ->log($description);
    }
}
