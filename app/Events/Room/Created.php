<?php

namespace App\Events\Room;

use Illuminate\Queue\SerializesModels;

class Created
{
  use SerializesModels;

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
