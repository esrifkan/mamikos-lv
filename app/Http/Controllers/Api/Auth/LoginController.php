<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  /**
   * @param \Dingo\Api\Http\Request   $request
   */
  public function owner(Request $request)
  {
    $this->validateLogin($request, "owner");

    /** Get the selected user. */
    $user = \App\User::where($this->username(), $request->input($this->username()))->with("roles")->first();
    if ($user && Hash::check($request->input("password"), $user->password)) {
      /** Get the roles as owner. */
      $role = config("roles.owner");
      /** Throws an error when the logged user is not an owner. */
      if (0 === $user->roles->where("name", $role["id"])->count()) {
        throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException(trans("auth.not.owner"));
      }

      return response()->json([
        "data" => [
          "token" => $user->createToken($role["id"], $role["capabilities"])->plainTextToken
        ]
      ]);
    }

    return $this->sendFailedLoginResponse($request);
  }

  /**
   * @param \Dingo\Api\Http\Request   $request
   */
  public function tenant(Request $request)
  {
    $this->validateLogin($request, "tenant");

    /** Get the selected user. */
    $user = \App\User::where($this->username(), $request->input($this->username()))->with("roles")->first();
    if ($user && Hash::check($request->input("password"), $user->password)) {
      /** Throws an error when the logged user is not a user. */
      if (0 === $user->roles->filter(function ($v) {
        return true === in_array($v->name, [config("roles.user-general.id"), config("roles.user-premium.id")]);
      })->count()) {
        throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException(trans("auth.not.user"));
      }

      $role = $user->roles->firstWhere("name", config("roles.user-premium.id"))
        ? config("roles.user-premium.id")
        : ($user->roles->firstWhere("name", config("roles.user-general.id")) ? config("roles.user-general.id") : config("roles.user-general.id"));
      $role = collect(config("roles"))->firstWhere("id", $role);

      return response()->json([
        "data" => [
          "token" => $user->createToken($role["id"], $role["capabilities"])->plainTextToken
        ]
      ]);
    }

    return $this->sendFailedLoginResponse($request);
  }

  /**
   * @param  \Dingo\Api\Http\Request  $request
   * @return \Symfony\Component\HttpFoundation\Response
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  protected function sendFailedLoginResponse(Request $request)
  {
    throw ValidationException::withMessages([
      $this->username() => [trans('auth.failed')],
    ]);
  }

  /**
   * @return string
   */
  protected function username()
  {
    return "email";
  }

  /**
   * @param \Dingo\Api\Http\Request   $request
   * @param string  $type
   */
  protected function validateLogin(Request $request, $type)
  {
    $request->validate([
      $this->username() => ["required", "string"],
      "password" => ["required", "string"]
    ]);
  }
}
