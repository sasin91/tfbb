<?php

namespace App\Events\Thread;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Fluent;

class ThreadUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $thread;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $this->thread->creator->load('profile');

        return [
            'thread' => new Fluent([
                'id' => $this->thread->id,
                'hashid' => $this->thread->hashid,
                'title' => $this->thread->title,
                'locked_at' => $this->thread->locked_at,
                'slug' => $this->thread->slug,
                'body' => $this->thread->body,
                'creator' => [
                    'id' => $this->thread->creator->id,
                    'name' => $this->thread->creator->name,
                    'profile' => ['link' => optional($this->thread->creator->profile)->link]
                ]
            ])
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("App.Board.{$this->thread->board_id}");
    }
}