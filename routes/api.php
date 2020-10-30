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

$api->version("v1", ["namespace" => "App\Http\Controllers\Api"], function ($api) {
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
});
