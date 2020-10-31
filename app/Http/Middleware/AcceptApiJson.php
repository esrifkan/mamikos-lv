<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AcceptApiJson {
  /**
   * handle an incoming request.
   *
   * @param \Illuminate\Http\Request  $request
   * @param \Closure  $next
   * @return mixed
   */
  public function handle($request, \Closure $next)
  {
    /** Make sure the Content-Type from incoming request is meet with the expected format. */
    if ("application/vnd.api+json" !== $request->header("Accept"))
      throw new BadRequestHttpException(
        sprintf("The requested header `Accept` should be in [%s].", "application/vnd.api+json")
      );

    return $next($request);
  }
}