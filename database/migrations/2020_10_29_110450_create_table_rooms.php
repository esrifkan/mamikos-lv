<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRooms extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("rooms", function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger("user_id");
      $table->string("title", 255);
      $table->longText("description")->nullable();
      $table->string("availability", 10)->default(0);
      $table->string("total", 10)->default(0);
      $table->string("lat", 10)->nullable();
      $table->string("lng", 10)->nullable();
      $table->unsignedBigInteger("price")->default(0);
      $table->timestamps();

      $table->foreign("user_id")->references("id")->on("users")->onUpdate("cascade")->onDelete("cascade");
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists("rooms");
  }
}
