<?php

namespace App;

use App\Concerns\RoutesUsingHashid;
use App\Concerns\Lockable;
use App\Concerns\Thread\ScrapsPostsWhenDeleted;
use App\Concerns\Thread\SummarizesBody;
use App\Events\Thread\ThreadCreated;
use App\Events\Thread\ThreadDeleted;
use App\Events\Thread\ThreadUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;
use Watson\Rememberable\Rememberable;

class Thread extends Model implements HasMediaConversions
{
	use ScrapsPostsWhenDeleted, 
        UsesMediaLibraryForFiles,
        RoutesUsingHashid,
        SummarizesBody,
        Lockable,
        Rememberable,
        Searchable,
        SoftDeletes;

    protected $fillable = [
     	'creator_id',
     	'board_id',
     	'title',
        'hashid',
     	'body',
        'summary'
    ];

    protected $dates = ['locked_at'];

    protected $appends = ['link'];

    protected $dispatchesEvents = [
        'created' => ThreadCreated::class,
        'deleted' => ThreadDeleted::class,
        'updated' => ThreadUpdated::class
    ];

    protected $perPage = 10;

    /**
     * The thread as it may feature in a popularity list.
     * 
     * @return array
     */
    public function toPopularArray()
    {
        return [
            'board_id' => $this->board_id,
            'title' => $this->title,
            'link' => $this->link
        ];
    }

    /**
     * The key used for building the slug.
     *
     * @return string
     */
    public function sluggable()
    {
        return 'title';
    }

    /**
     * Get the link to this thread.
     * 
     * @return string
     */
    public function getLinkAttribute()
    {
        return url("threads", $this);
    }

    /**
     * The creator of the thread.
     *     
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator() 
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * A thread is part of a board.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function board() 
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * A thread consists of replies.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies() 
    {
    	return $this->hasMany(Reply::class)->latest();
    }

    /**
     * Register conversions for media uploaded to the model
     * 
     * @param  Media|null $media 
     * @return void
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumbnail')
              ->width(368)
              ->height(232)
              ->sharpen(10)
              ->performOnCollections('photos');
    }

    /**
     * Get the photo URLs
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getPhotoUrls()
    {
        return $this->getMedia('photos')->map(function ($media) {
            return [
                'thumbnail' => $media->getUrl('thumbnail'),
                'url' => $media->getUrl()
            ];
        });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'summary' => $this->summary,
            'creator' => $this->creator->name,
            'board' => $this->board->name,
            'link' => $this->link
        ];
    }
}
