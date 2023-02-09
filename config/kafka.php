<?php

return [
    'broker' => env('KAFKA_BROKER'),
    'version' => env('KAFKA_BROKER_VERSION'),
    'refresh_interval_ms' => env('KAFKA_REFRESH_INTERVAL_MS'),
    'produce_interval' => env('KAFKA_REFRESH_INTERVAL_MS'),
    'required_ack' => env('KAFKA_REQUIRED_ACK'),
];
