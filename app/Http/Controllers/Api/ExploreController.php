<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collection\Room as CollectionRoom;
use Dingo\Api\Http\Request;

class ExploreController extends Controller
{
  /**
   * @param \Dingo\Api\Http\Request   $request
   */
  public function search(Request $request)
  {
    /** Get the current user. */
    $user = $request->user();

    $this->assureCanAccess(true === $user->tokenCan("room:explore"));

    $filter = $request->input("filter");
    $rooms = \App\Room::with("location")->orderBy("price", $request->input("sort") ?: "ASC");
    /** Filter by name. */
    if (false === empty($filter["name"])) {
      $rooms = $rooms->where("title", "like", "%{$filter["name"]}%");
    }

    /** Filter by price. */
    if (false === empty($filter["price"])) {
      $__price = explode("~", $filter["price"]);
      $rooms = $rooms->where("price", ">=", $__price[0]);
      if (true == isset($__price[1])) {
        $rooms = $rooms->where("price", "<=", $__price[1]);
      }
    }

    /** Filter by location. */
    if (false === empty($filter["location"])) {
      $rooms = $rooms->whereHas("location", function ($query) use ($filter) {
        $query->whereIn("id", array_filter(explode(",", $filter["location"])));
      });
    }

    return new CollectionRoom($rooms->get(), $user);
  }
}
