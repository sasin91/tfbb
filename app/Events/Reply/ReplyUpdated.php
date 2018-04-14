<?php

namespace App\Events\Reply;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Fluent;

class ReplyUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reply)
    {
        $this->reply = $reply;

        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $this->reply->load(['creator', 'creator.profile']);

        return [
            'reply' => new Fluent([
                'id' => $this->reply->id,
                'hashid' => $this->reply->hashid,
                'title' => $this->reply->title,
                'body' => $this->reply->body,
                'creator' => [
                    'id' => $this->reply->creator->id,
                    'name' => $this->reply->creator->name,
                    'profile' => ['link' => optional($this->reply->creator->profile)->link]
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
        return new Channel("App.Thread.{$this->reply->thread_id}");
    }
}