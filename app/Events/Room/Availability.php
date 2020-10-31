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
   * @var \App\Role
   */
  public $role;

  /**
   * @var int
   */
  public $amount = 0;

  /**
   * Create a new event instance.
   *
   * @param \App\Room
   * @return void
   */
  public function __construct(\App\Room $room, \App\User $user, \App\Role $role)
  {
    $this->room = $room;
    $this->user = $user;
    $this->role = $role;

    $__selected = collect(config("roles"))->firstWhere("id", $this->role->name);
    if (false === empty($__selected["credit"]["room"]["availability"])) {
      $__amount = $this->role->pivot->credit + $__selected["credit"]["room"]["availability"];
      $this->amount = 0 < $__amount ? $__amount : 0;
    }
  }
}
