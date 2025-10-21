<?php

return [
    // credit cost per action
    'costs' => [
        'campaign.create' => 10,
        'campaign.run'    => 5,
        'api.request'     => 1,
    ],

    // how often to send low-credit alerts (seconds)
    'low_event_gap_seconds' => 3600,
];
