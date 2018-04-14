<?php return [
    /*
    |--------------------------------------------------------------------------
    | HashIds default values
    |--------------------------------------------------------------------------
    |
    | These are the defaults to be used when generating hashids, without a model specific config.
    */
    'default'  =>  [
        'salt'          =>  env('HASHIDS_SALT', ''),
        'length'        =>  16,
        'alphabet'      =>  'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ]
];