<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | This value is the API key for OpenAI. You can get this from your OpenAI
    | dashboard at https://platform.openai.com/api-keys
    |
    */

    'api_key' => env('OPENAI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Organization
    |--------------------------------------------------------------------------
    |
    | This value is the organization ID for OpenAI. This is optional but can
    | be useful for billing and usage tracking.
    |
    */

    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | Default Model
    |--------------------------------------------------------------------------
    |
    | This value is the default model to use for chat completions.
    |
    */

    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),

    /*
    |--------------------------------------------------------------------------
    | Default Parameters
    |--------------------------------------------------------------------------
    |
    | These are the default parameters for OpenAI API calls.
    |
    */

    'defaults' => [
        'max_tokens' => 500,
        'temperature' => 0.7,
        'top_p' => 1,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    ],
];

