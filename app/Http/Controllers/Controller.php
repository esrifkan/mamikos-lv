<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  /**
   * Assure the user's credit is enough to do the request.
   *
   * @param \App\Role     $role
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   */
  protected function assureEnoughCredit(\App\Role $role)
  {
    if (0 >= $role->pivot->credit) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("You don't have enough credit point to access this action.");
    }
  }
}
