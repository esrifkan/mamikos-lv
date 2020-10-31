<?php

namespace App\Http\Resources;

use App\Foundation\Http\Resources\Json\Resource;

class Room extends Resource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      "id" => $this->id,
      "type" => "Room",
      "attributes" => [
        "description" => $this->description,
        "lat" => $this->lat,
        "lng" => $this->lng,
        "price" => $this->when($this->user && $this->user->tokenCan("room:detail"), $this->price),
        "title" => $this->title,
        "createdAt" => $this->created_at,
        "updatedAt" => $this->updated_at,
      ],
      "links" => [
        "self" => route("get.room.show", ["code" => $this->id])
      ],
      "relationships" => [
        "location" => $this->relationLoaded("location") ? new Location($this->location, $this->user) : null
      ]
    ];
  }
}
