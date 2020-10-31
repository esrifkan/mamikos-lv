<?php

namespace App\Events\Room;

use App\Events\Event;

class Updated extends Event
{
  /**
   * @var \App\Room
   */
  public $room;

  /**
   * Create a new event instance.
   *
   * @param \App\Room
   * @return void
   */
  public function __construct(\App\Room $room)
  {
    $this->room = $room;
  }
}
