<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
  /**
   * @var array
   */
  protected $fillable = [
    "name"
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function user(): BelongsToMany
  {
    return $this->belongsToMany(User::class, "user_has_roles");
  }
}
