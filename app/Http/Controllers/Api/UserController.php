<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;

class UserController extends Controller {
  /**
   * @param \Dingo\Api\Http\Request   $request
   */
  public function me(Request $request) {
    $user = $request->user();
    return response()->json([
      "data" => [
        "email" => $user->email,
        "name" => $user->name
      ]
    ]);
  }
}