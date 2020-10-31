<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
  /**
   * @var array
   */
  protected $fillable = [
    "description", "title"
  ];

  /**
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function rooms(): HasMany
  {
    return $this->hasMany(Room::class);
  }
}
