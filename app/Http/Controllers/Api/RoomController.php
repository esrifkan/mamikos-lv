<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
  /**
   * @param string  $code
   * @param \Dingo\Api\Http\Request   $request
   */
  public function delete(string $code, Request $request)
  {
    $user = $request->user();

    if (false === $user->tokenCan("room:delete")) {
      throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException("Unauthorized", "You don't have the right permission to access this action.");
    }

    /** Get the selected room. */
    $room = \App\Room::where("id", $code)->whereHas("user", function ($query) use ($user) {
      $query->where("id", $user->id);
    })->first();

    if (!$room) {
      /** Throws an error. */
      throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException("Whoops, the room were not exist.");
    }

    /** Delete the room. */
    $delete = $room->delete();

    /** Dispatch event. */
    event(new \App\Events\Room\Deleted($room));

    return response()->json([
      "data" => [
        "delete" => $delete
      ]
    ]);
  }

  /**
   * @param \Dingo\Api\Http\Request $request
   */
  public function store(Request $request)
  {
    $user = $request->user();

    if (false === $user->tokenCan("room:create")) {
      throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException("Unauthorized", "You don't have the right permission to access this action.");
    }

    /** Validate the incoming request. */
    Validator::make($request->all(), [
      "description" => ["nullable"],
      "lat" => ["nullable", "numeric"],
      "lng" => ["nullable", "numeric"],
      "title" => ["required", "string", "max:255"],
      "total" => ["required", "numeric", "min:1"]
    ])->validate();

    /** Define room. */
    $room = new \App\Room([
      "description" => $request->input("description"),
      "lat" => $request->input("lat") ?: null,
      "lng" => $request->input("lng") ?: null,
      "title" => $request->input("title"),
      "total" => $request->input("total"),
    ]);

    /** Store the room for logged user. */
    $request->user()->rooms()->save(
      $room
    );

    /** Dispatch event. */
    event(new \App\Events\Room\Created($room));

    return response()->json([
      "data" => $room
    ]);
  }
}
