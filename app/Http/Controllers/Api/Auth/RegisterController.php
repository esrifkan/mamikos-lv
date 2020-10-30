<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
  /**
   * @param \Dingo\Api\Http\Request $request
   * @return mixed
   */
  public function owner(Request $request)
  {
    $this->validateRequest($request, "owner");

    list($user, $token) = $this->create($request->all(), config("roles.owner.id"));
    event(new \Illuminate\Auth\Events\Registered($user));

    return $this->registered($user, $token);
  }

  /**
   * @param \Dingo\Api\Http\Request $request
   * @return mixed
   */
  public function tenant(Request $request)
  {
    $this->validateRequest($request, "tenant");

    list($user, $token) = $this->create($request->all(), $request->input("type"));
    event(new \Illuminate\Auth\Events\Registered($user));

    return $this->registered($user, $token);
  }

  /**
   * @param array   $data
   * @return array
   */
  protected function create(array $data, $role)
  {
    $user = null;
    $token = null;
    DB::transaction(function () use ($data, $role, &$user, &$token) {
      $user = \App\User::create([
        "name" => $data["name"],
        "email" => $data["email"],
        "password" => Hash::make($data["password"])
      ]);

      /** Get the selected role. */
      $role = collect(config("roles"))->firstWhere("id", $role);

      /** Attach the role. */
      $user->roles()->attach(
        \App\Role::where("name", $role["id"])->first()->id
      );

      /** Generate access token. */
      $token = $user->createToken($role["id"], $role["capabilities"])->plainTextToken;
    });

    return [$user, $token];
  }

  /**
   * @param \Illuminate\Contracts\Auth  $user
   * @param string $token
   */
  protected function registered($user, $token)
  {
    return response()->json([
      "data" => [
        "token" => $token
      ]
    ], 201);
  }

  /**
   * @param \Dingo\Api\Http\Request   $request
   * @param string  $type
   * @return void
   */
  protected function validateRequest(Request $request, $type)
  {
    $rules = [
      "email" => ["required", "string", "email", "max:255", "unique:users"],
      "name" => ["required", "string", "max:255"],
      "password" => ["required", "string", "min:6", "confirmed"],
    ];

    switch ($type) {
      case "tenant":
        $rules["type"] = ["required", Rule::in([config("roles.user-general.id"), config("roles.user-premium.id")])];
        break;
    }
    $request->validate($rules);
  }
}
