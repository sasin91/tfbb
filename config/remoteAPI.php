<?php return [
    /*
    |--------------------------------------------------------------------------
    | Default Remote API
    |--------------------------------------------------------------------------
    |
    | This option controls the default api to sync models with,
    | and fetch results for.
    |
    | Supported: "wger", "null"
    |
    */
	'driver' => env('REMOTE_API', 'Wger')
];