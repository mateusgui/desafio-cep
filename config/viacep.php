<?php

return [
    'base_url'     => env('VIACEP_URL', 'https://viacep.com.br/ws'),
    'timeout'      => (int) env('VIACEP_TIMEOUT', 5),
    'retry_ms'     => (int) env('VIACEP_RETRY_MS', 500),
    'intervalo_ms' => (int) env('VIACEP_INTERVALO_MS', 200),
];