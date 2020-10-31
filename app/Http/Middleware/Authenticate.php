<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
  /**
   * Handle an unauthenticated user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  array  $guards
   * @return void
   *
   * @throws \Illuminate\Auth\AuthenticationException
   */
  protected function unauthenticated($request, array $guards)
  {
    if (!$request->expectsJson()) {
      return route('login');
    }

    throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException("Unauthorized", "Unauthenticated.");
  }
}
