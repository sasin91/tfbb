<?php

namespace App\Jobs\RemoteAPI;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PushToRemoteAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The model to be synced to the remote API
     * 
     * @var \Illuminate\Database\Eloquent\Model | \App\Concerns\InteractsWithRemoteAPI
     */
    public $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->model->wasRecentlyCreated) {
            $response = $this->model->getAPIResource()->post($this->model);

            $result = json_decode($response->getBody()->getContents(), true);

            $this->model->forceFill(['provider_id' => $result['id']])->saveOrFail();
        } else {
            $this->model->getAPIResource()->put(
                $this->model->provider_id,
                $this->model
            );
        }
    }
}
