<?php

namespace App\Foundation\Http\Resources\Json;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class Resource extends JsonResource
{
  /**
   * The "data" wrapper that should be applied.
   *
   * @var string
   */
  public static $wrap = "data";

  /**
   * @var \App\User
   */
  protected $user;

  /**
   * @param mixed   $resource
   * @param \App\User|null   $user
   * @return void
   */
  public function __construct($resource = null, $user = null)
  {
    parent::__construct($resource);
    $this->user = $user;
  }

  /**
   * Set the given resource's grammar.
   *
   * @param \App\User|null  $user
   * @return \App\Foundation\Http\Resources\Json\Resource
   */
  public function setUser($user)
  {
    $this->user = $user;
    return $this;
  }

  /**
   * Customize a response for the request.
   *
   * @param \Illuminate\Http\Request  $request
   * @param \Illuminate\Http\Response   $response
   * @return void
   */
  public function withResponse($request, $response)
  {
    switch (true) {
      case true === $request->isMethod("post"):
        $response->setStatusCode(201);
        break;
    }

    $response->header("Content-Type", "application/vnd.api+json");
  }
}
