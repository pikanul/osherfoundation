<?php

return [
    'json_path' => env('LICENSE_UPDATE_JSON_PATH', storage_path('app/license-update/data.json')),
    'token' => env('UPDATE_API_TOKEN', ''),
];

