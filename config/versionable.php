<?php return [
	/*
    |--------------------------------------------------------------------------
    | Queue Model versioning
    |--------------------------------------------------------------------------
    |
    | This option allows you to control if creating versions should be queued. 
    | When this is set to "true" then versioning unless directly invoked, will get queued onto the default pipe.
    | You may also specify which queue to use, by passing a string.
    | 
    */

    'queue' => env('VERSIONABLE_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Create original version
    |--------------------------------------------------------------------------
    |
    | Whether we should create a version of a freshly created model.
    | 
    | Supported: "true", "false"
    |
    */
   
    'original' => true,

    /*
    |--------------------------------------------------------------------------
    | Model ressurection
    |--------------------------------------------------------------------------
    |
    | Whether we should retain versions of a deleted model to allow em to be used to ressurecting a deleted model.
    | optionally, how long the ressurection should be available.
    | the ressurection lifetime, should be a valid DateTime interval. eg. "30 days"
    | 
    | Supported: "true", "false", "string"
    */
   
    'ressurection' => false,
];