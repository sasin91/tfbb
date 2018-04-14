<?php

namespace App;

use App\Concerns\Commentable;
use App\Concerns\Mentions\NotifiesMentionees;
use App\Concerns\RoutesUsingHashid;
use App\Events\Reply\ReplyCreated;
use App\Events\Reply\ReplyDeleted;
use App\Events\Reply\ReplyUpdated;
use App\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Rememberable\Rememberable;

class Reply extends Model
{
	use RoutesUsingHashid, NotifiesMentionees, Commentable, SoftDeletes;

    protected $fillable = [
    	'creator_id',
        'thread_id',
    	'title',
    	'body',
        'hashid'
    ];

    protected $dispatchesEvents = [
        'created' => ReplyCreated::class,
        'updated' => ReplyUpdated::class,
        'deleted' => ReplyDeleted::class
    ];

    /**
     * The thread this post is part of.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread() 
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * The creator of the current post.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator() 
    {
    	return $this->belongsTo(User::class, 'creator_id');
    }
}
