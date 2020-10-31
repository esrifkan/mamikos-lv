<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
  /**
   * @var array
   */
  protected $fillable = [
    "description", "lat", "lng", "price", "title", "total",
  ];

  /**
   * @var array
   */
  protected $casts = [
    "availability" => "integer",
    "lat" => "float",
    "lng" => "float",
    "price" => "integer",
    "total" => "integer",
  ];

  /**
   * @var void
   */
  public static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->total = $model->total ?: 0;
      /** Auto define `availability` when the value doesn't exist. */
      $model->availability = $model->availability
        ? ($model->availability > $model->total ? $model->total : $model->availability)
        : $model->total;
    });

    static::updating(function ($model) {
      /** Force update the `availability` when greater than `total`. */
      $model->availability = $model->availability > $model->total ? $model->total : $model->availability;
    });
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function location(): BelongsTo
  {
    return $this->belongsTo(Location::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, "user_id");
  }
}
