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
  public function availability(string $code, Request $request)
  {
    /** Get the current user. */
    $user = $request->user();

    if (false === $user->tokenCan("room:availability")) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("You don't have the right permission to access this action.");
    }

    /** Make sure the credit point still enough. */
    $token = $user->currentAccessToken();

    /** Get the current user's role is being used. */
    $role = $user->roles->firstWhere("name", $token->name);

    /** Make sure the user's credit still enough. */
    $this->assureEnoughCredit($role);

    /** get the selected room. */
    $room = \App\Room::where("id", $code)->first();
    if (!$room) {
      /** Throws an error. */
      throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException("Whoops, the room were not exist.");
    }

    /** Dispatch event */
    event(new \App\Events\Room\Availability($room, $user, $role));

    return response()->json([
      "data" => [
        "availability" => 0 < $room->availability
      ]
    ]);
  }

  /**
   * @param string  $code
   * @param \Dingo\Api\Http\Request   $request
   */
  public function delete(string $code, Request $request)
  {
    $user = $request->user();

    if (false === $user->tokenCan("room:delete")) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("You don't have the right permission to access this action.");
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
   * @param \Dingo\Api\Http\Request   $request
   */
  public function index(Request $request)
  {
    /** Get the current user. */
    $user = $request->user();

    if (false === $user->tokenCan("room:list")) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("You don't have the right permission to access this action.");
    }

    /** Fetch the rooms for current user. */
    $rooms = \App\Room::whereHas("user", function($query) use($user) {
      $query->where("id", $user->id);
    })->orderBy("created_at", "DESC")->get();

    return response()->json([
      "data" => $rooms
    ]);
  }

  /**
   * @param \Dingo\Api\Http\Request $request
   */
  public function store(Request $request)
  {
    $user = $request->user();

    if (false === $user->tokenCan("room:create")) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("You don't have the right permission to access this action.");
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

  /**
   * @param string  $code
   * @param \Dingo\Api\Http\Request $request
   */
  public function update(string $code, Request $request)
  {
    $user = $request->user();

    if (false === $user->tokenCan("room:edit")) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("You don't have the right permission to access this action.");
    }

    /** Get the selected room. */
    $room = \App\Room::where("id", $code)->whereHas("user", function ($query) use ($user) {
      $query->where("id", $user->id);
    })->first();

    if (!$room) {
      /** Throws an error. */
      throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException("Whoops, the room were not exist.");
    }

    /** Validate incoming request. */
    $maxTotal = $request->has("total") ?: $room->total;
    Validator::make($request->all(), [
      "availability" => ["nullable", "numeric", "min:0", "max:{$maxTotal}"],
      "description" => ["nullable"],
      "lat" => ["nullable", "numeric"],
      "lng" => ["nullable", "numeric"],
      "title" => ["nullable", "string", "max:255"],
      "total" => ["nullable", "numeric", "min:1"]
    ])->validate();

    foreach ($request->only(["availability", "description", "lat", "lng", "title", "total"]) as $key => $value) {
      $room->{$key} = $value;
    }

    /** Update the room. */
    $room->save();

    /** Trigger event when the room's attributes were changed. */
    if (true === $room->wasChanged()) {
      event(new \App\Events\Room\Updated($room));
    }

    return response()->json([
      "data" => $room
    ]);
  }
}
