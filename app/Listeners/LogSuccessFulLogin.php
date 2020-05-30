<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Laravue\Models\Activity;

class LogSuccessFulLogin
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {

    $subject = "Login efetuado";
    $description = $event->user->email . ' efetuou login';

       $activity = activity($subject)
           ->causedBy($event->user->id)
           ->performedOn($event->user)
           ->log($description);
    }
}
