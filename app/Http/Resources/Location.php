<?php

namespace App\Http\Resources;

use App\Foundation\Http\Resources\Json\Resource;

class Location extends Resource
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
      "type" => "Location",
      "attributes" => [
        "description" => $this->description,
        "title" => $this->title,
      ],
    ];
  }
}
