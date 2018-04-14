<?php

namespace App\Jobs\Board;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ScrapThreadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The board we're scrapped the threads of.
     * 
     * @var \App\Board
     */
    public $board;

    /**
     * Create a new job instance.
     *
     * @param  \App\Board $board 
     * @return void
     */
    public function __construct($board)
    {
        $this->board = $board;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->board->threads->each->delete();
    }
}
