<?php

use Facade\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app(\Dingo\Api\Routing\Router::class);

$api->version("v1", ["middleware" => ["api"], "namespace" => "App\Http\Controllers\Api"], function ($api) {
  $api->group(["namespace" => "Auth", "prefix" => "auth"], function ($api) {
    $api->group(["prefix" => "/register"], function ($api) {
      $api->post("/owner", "RegisterController@owner")->name("post.register.owner");
      $api->post("/tenant", "RegisterController@tenant")->name("post.register.tenant");
    });
    $api->group(["prefix" => "/token"], function ($api) {
      $api->post("/owner", "LoginController@owner")->name("post.login.token.owner");
      $api->post("/tenant", "LoginController@tenant")->name("post.login.token.tenant");
    });
  });

  $api->group(["middleware" => ["auth:sanctum"]], function ($api) {
    $api->get("/explore", "ExploreController@search")->name("explore.search");
  });

  $api->group(["prefix" => "rooms"], function ($api) {
    $api->group(["middleware" => "auth:sanctum"], function ($api) {
      $api->get("/", "RoomController@index")->name("get.room.list");
      $api->post("/", "RoomController@store");
      $api->group(["prefix" => "/{code}"], function ($api) {
        $api->delete("/", "RoomController@delete");
        $api->get("/", "RoomController@show")->name("get.room.show");
        $api->put("/", "RoomController@update");
        $api->get("/availability", "RoomController@availability");
      });
    });
  });
});
