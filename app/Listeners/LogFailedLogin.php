<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Laravue\Models\Activity;

class LogFailedLogin
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
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $subject = "Login falhou";
        $description = $event->user->email . ' falhou ao tentar efetuar login';
        
       $activity = activity($subject)
           ->causedBy($event->user->id)
           ->performedOn($event->user)
           ->log($description);
    }
}
