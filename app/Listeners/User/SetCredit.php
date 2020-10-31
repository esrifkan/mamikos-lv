<?php

namespace App\Listeners\User;

class SetCredit
{
  /**
   * Handle the event.
   *
   * @param \App\Events\Room\Availability   $event
   * @return void
   */
  public function handle(\App\Events\Room\Availability $event)
  {
    if ($event->user) {
      $event->user->roles()->updateExistingPivot($event->role->id, ["credit" => $event->amount]);
    }
  }
}
