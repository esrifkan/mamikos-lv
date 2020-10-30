<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $roles = [
      [
        "name" => config("roles.owner.id")
      ],
      [
        "name" => config("roles.user-general.id")
      ],
      [
        "name" => config("roles.user-premium.id")
      ]
    ];

    foreach ($roles as $value) {
      \App\Role::updateOrCreate($value, []);
    }
  }
}
