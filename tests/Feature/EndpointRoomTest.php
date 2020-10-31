<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EndpointRoomTest extends TestCase
{
  public function testRoomListsUnauthorized()
  {
    $response = $this->get("/api/rooms", [
      "Accept" => "application/vnd.api+json",
      "Content-Type" => "application/vnd.api+json",
    ]);
    $response->assertStatus(401);
  }

  public function testRoomUnauthorized()
  {
    $response = $this->get("/api/rooms/1", [
      "Accept" => "application/vnd.api+json",
      "Content-Type" => "application/vnd.api+json",
    ]);
    $response->assertStatus(401);
  }

  public function testRoomPostUnauthorized()
  {
    $response = $this->post("/api/rooms", [], [
      "Accept" => "application/vnd.api+json",
      "Content-Type" => "application/vnd.api+json",
    ]);
    $response->assertStatus(401);
  }

  public function testRoomPutUnauthorized()
  {
    $response = $this->put("/api/rooms/1", [], [
      "Accept" => "application/vnd.api+json",
      "Content-Type" => "application/vnd.api+json",
    ]);
    $response->assertStatus(401);
  }

  public function testRoomDeleteUnauthorized()
  {
    $response = $this->delete("/api/rooms/1", [], [
      "Accept" => "application/vnd.api+json",
      "Content-Type" => "application/vnd.api+json",
    ]);
    $response->assertStatus(401);
  }
}
