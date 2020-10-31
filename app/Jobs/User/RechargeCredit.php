<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RechargeCredit implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * @var \Illuminate\Support\Collection[\App\User]
   */
  public $users;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->connection = "database";
    $this->queue = "default";

    $users = collect([]);
    \App\User::with("roles")->chunk(2, function ($v) use (&$users) {
      $users->push($v);
    });

    $this->users = $users;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $this->users->each(function ($group) {
      $group->each(function ($user) {
        dispatch(new \App\Jobs\User\ResetCredit($user));
      });
    });
  }
}
