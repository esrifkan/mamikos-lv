<?php

namespace App\Events\Room;

use App\Events\Event;

class Availability extends Event
{
  /**
   * @var \App\Room
   */
  public $room;

  /**
   * @var \App\User
   */
  public $user;

  /**
   * Create a new event instance.
   *
   * @param \App\Room
   * @return void
   */
  public function __construct(\App\Room $room, \App\User $user)
  {
    $this->room = $room;
    $this->user = $user;
  }
}
