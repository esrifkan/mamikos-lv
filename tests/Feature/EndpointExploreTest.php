<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EndpointExploreTest extends TestCase
{
  public function testRoomListsUnauthorized()
  {
    $response = $this->get("/api/explore", [
      "Accept" => "application/vnd.api+json",
      "Content-Type" => "application/vnd.api+json",
    ]);
    $response->assertStatus(401);
  }
}
