<?php

namespace App;

use App\Concerns\Mentions\NotifiesMentionees;
use App\Concerns\RoutesUsingHashid;
use App\Concerns\RoutesUsingSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
	use RoutesUsingHashid, NotifiesMentionees, SoftDeletes;

    protected $fillable = [
    	'creator_id',
    	'title',
    	'body',
        'hashid'
    ];

    /**
     * The creator of the comment.
     *     
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator() 
    {
    	return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Morph the comment into the commentable model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable() 
    {
    	return $this->morphTo();
    }
}
