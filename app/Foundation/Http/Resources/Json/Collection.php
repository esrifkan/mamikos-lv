<?php

namespace App\Foundation\Http\Resources\Json;

use Countable;
use Illuminate\Http\Resources\CollectsResources;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use IteratorAggregate;

class Collection extends Resource implements Countable, IteratorAggregate
{
  use CollectsResources;

  /**
   * The resource that this resource collects.
   *
   * @var string
   */
  public $collects;

  /**
   * The mapped collection instance.
   *
   * @var \Illuminate\Support\Collection
   */
  public $collection;

  /**
   * Create a new resource instance.
   *
   * @param mixed   $resource
   * @param \App\User|null  $user
   */
  public function __construct($resource, $user)
  {
    parent::__construct($resource, $user);

    $this->resource = $this->collectResource($resource);
    switch (TRUE) {
      case $this->resource instanceof LengthAwarePaginator:
        $this->resource->getCollection()->map(function ($item) use ($user) {
          $item->setUser($user);
          return $item;
        });
        break;
      case $this->resource instanceof SupportCollection:
        $this->resource->map(function ($item) use ($user) {
          $item->setUser($user);
          return $item;
        });
        break;
    }
  }

  /**
   * Return the count of items in the resource collection.
   *
   * @return int
   */
  public function count()
  {
    return $this->collection->count();
  }

  /**
   * Transform the resource into a JSON array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return $this->collection->map->toArray($request)->all();
  }

  /**
   * Create an HTTP response that represents the object.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function toResponse($request)
  {
    return $this->resource instanceof AbstractPaginator
      ? (new PaginatedResourceResponse($this))->toResponse($request)
      : parent::toResponse($request);
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
    $response->header("Content-Type", "application/vnd.api+json");
  }
}
