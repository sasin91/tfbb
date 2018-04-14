<?php

namespace App\Concerns;

use App\Jobs\RemoteAPI\PushToRemoteAPI;
use App\Services\RemoteAPI;

/**
 * Enable interacting with a remote API,
 * this trait assumes the model has a provider_id attribute.
 */
trait SyncsWithRemoteAPI
{
	public static function bootSyncsWithRemoteAPI()
	{
		static::created(function ($model) {
			if ($model->shouldSyncRemoteAPI()) {
				dispatch(new PushToRemoteAPI($model));
			}
		});

		static::updated(function ($model) {
			if ($model->shouldSyncRemoteAPI()) {
				dispatch(new PushToRemoteAPI($model));
			}
		});

        static::deleted(function ($model) {
            if ($model->shouldSyncRemoteAPI()) {
                $model->provider()->delete(
                    $model->provider_id
                );
            }
        }); 
	}	

	/**
	 * Whether we should sync with remote API
	 * 
	 * @return boolean
	 */
	public function shouldSyncRemoteAPI()
	{
		return filled($this->provider);
	}

    /**
     * Get the Provider client
     * 
     * @return \App\Services\Wger\WgerAPIResource
     */
    public function getAPIResource()
    {
    	return RemoteAPI::resource($this->apiResource());
    }

    /**
     * The resource name on the remote api this model represents
     * 	
     * @return string
     */
    public function apiResource()
    {
    	return class_basename($this);
    }
}