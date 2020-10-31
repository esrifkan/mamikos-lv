<?php

use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $locations = [
      [
        "title" => "Bantul"
      ],
      [
        "title" => "Gunung Kidul"
      ],
      [
        "title" => "Kulon Progo"
      ],
      [
        "title" => "Sleman"
      ],
      [
        "title" => "Kota Yogyakarta"
      ]
    ];

    foreach ($locations as $value) {
      \App\Location::updateOrCreate($value, []);
    }
  }
}
