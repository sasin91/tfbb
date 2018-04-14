<?php

namespace App\Jobs;

use App\Notifications\User\YouWereMentioned;
use Facades\App\Inspections\Mentions\Mentions;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyMentioneesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The model the user was mentioned in.
     *     
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * The attribute containing the text to be scanned for mentionees.
     *     
     * @var string
     */
    public $attribute;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $attribute = 'body')
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = (string)$this->model->{$this->attribute};
        
        if (Mentions::check($content)) {
            Mentions::in($content)->each(function ($user) {
                $user->notify(new YouWereMentioned($this->model));
            });
        }
    }
}
