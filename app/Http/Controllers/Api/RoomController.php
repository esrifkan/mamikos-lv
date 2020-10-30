<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{

  /**
   * @param \Dingo\Api\Http\Request $request
   */
  public function store(Request $request)
  {
    $user = $request->user();

    if (true === $user->tokenCan("room:create")) {
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

    throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException("Unauthorized", "You don't have the right permission to access this action.");
  }
}
