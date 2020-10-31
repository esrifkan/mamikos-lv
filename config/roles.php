<?php

return [
  "owner" => [
    "id" => "owner",
    "title" => "Owner",
    "capabilities" => [
      "room:create",
      "room:edit",
      "room:delete",
      "room:list",
      "room:detail",
    ],
    "credit" => [
      "register" => 0
    ]
  ],
  "user-general" => [
    "id" => "user-general",
    "title" => "User",
    "capabilities" => [
      "room:availability",
      "room:detail",
      "room:explore"
    ],
    "credit" => [
      "register" => 20,
      "room" => [
        "availability" => -5
      ]
    ]
  ],
  "user-premium" => [
    "id" => "user-premium",
    "title" => "User Premium",
    "capabilities" => [
      "room:availability",
      "room:detail",
      "room:explore"
    ],
    "credit" => [
      "register" => 40,
      "room" => [
        "availability" => -5
      ]
    ]
  ]
];
