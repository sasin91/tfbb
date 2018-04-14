<?php

namespace App;

use App\Concerns\Board\ScrapsThreadsWhenDeleted;
use App\Concerns\Publishable;
use App\Concerns\RoutesUsingSlug;
use App\Events\Board\BoardCreated;
use App\Events\Board\BoardDeleted;
use App\Events\Board\BoardUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Watson\Rememberable\Rememberable;

class Board extends Model
{
    use RoutesUsingSlug,
    	ScrapsThreadsWhenDeleted,
        Searchable,
        Publishable,
        Rememberable,
        SoftDeletes;

    protected $fillable = [
        'published_at',
        'name',
        'slug',
        'description'
    ];

    protected $casts = [
        'threads_count' => 'integer',
        'published_at' => 'datetime'
    ];

    protected $dispatchesEvents = [
        'created' => BoardCreated::class,
        'updated' => BoardUpdated::class,
        'deleted' => BoardDeleted::class
    ];

    /**
     * Get the link to this board.
     * 
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('/boards', $this);
    }

    /**
     * A board consists of threads.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads() 
    {
    	return $this->hasMany(Thread::class)->latest();
    }
    
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'link' => $this->link
        ];
    }

    /**
     * Get the array for the popularity score.
     * 
     * @return array
     */
    public function toPopularArray()
    {
        return [
            'name' => $this->name,
            'link' => $this->link,
        ];
    }
}
