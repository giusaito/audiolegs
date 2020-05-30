<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Laravue\Models\Activity;

class LogPasswordReset
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
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $subject = "Resetou a senha";
        $description = $event->user->email . ' resetou a senha';

       $activity = activity($subject)
           ->causedBy($event->user->id)
           ->performedOn($event->user)
           ->log($description);
    }
}
