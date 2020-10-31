<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLocations extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("locations", function (Blueprint $table) {
      $table->id();
      $table->string("title");
      $table->longText("description")->nullable();
      $table->timestamps();
    });

    Schema::table("rooms", function (Blueprint $table) {
      if (false === Schema::hasColumn("rooms", "location_id")) {
        $table->unsignedBigInteger("location_id")->after("user_id");
        $table->foreign("location_id")->references("id")->on("locations")->onDelete("cascade");
      }
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists("locations");
  }
}
