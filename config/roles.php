<?php

return [
  "owner" => [
    "id" => "owner",
    "title" => "Owner",
    "capabilities" => [
      "roomCreate",
      "roomEdit",
      "roomDelete",
      "roomList",
      "roomDetail",
    ],
    "credit" => [
      "register" => 0
    ]
  ],
  "user-general" => [
    "id" => "user-general",
    "title" => "User",
    "capabilities" => [
      "askRoomAvailability",
      "roomDetail",
    ],
    "credit" => [
      "register" => 20
    ]
  ],
  "user-premium" => [
    "id" => "user-premium",
    "title" => "User Premium",
    "capabilities" => [
      "askRoomAvailability",
      "roomDetail",
    ],
    "credit" => [
      "register" => 40
    ]
  ]
];
