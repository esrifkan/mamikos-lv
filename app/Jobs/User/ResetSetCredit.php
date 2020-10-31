<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResetCredit implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * @var \App\User
   */
  public $user;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(\App\User $user)
  {
    $this->connection = "database";
    $this->queue = "default";
    $this->user = $user;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $roles = collect(config("roles"));
    foreach ($this->user->roles as $item) {
      $role = $roles->firstWhere("id", $item->name);
      $value = false === empty($role["credit"]["register"]) ? $role["credit"]["register"] : 0;
      $this->user->roles()->updateExistingPivot($item->pivot->role_id, [
        "credit" => $value
      ]);
    }
  }
}
