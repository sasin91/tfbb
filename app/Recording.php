<?php

namespace App;

use App\Concerns\GeneratesSummaryByClampingBody;
use App\Concerns\RoutesUsingSlug;
use App\Events\Recording\RecordingCreated;
use App\Events\Recording\RecordingDeleted;
use App\Events\Recording\RecordingUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Recording extends Model implements HasMediaConversions
{	
	use GeneratesSummaryByClampingBody, RoutesUsingSlug, UsesMediaLibraryForFiles, Searchable, SoftDeletes;

	protected $fillable = [
		'category', 
		'title', 
		'slug', 
		'summary', 
		'body'
	];

    protected $appends = ['link'];

    protected $dispatchesEvents = [
        'created' => RecordingCreated::class,
        'updated' => RecordingUpdated::class,
        'deleted' => RecordingDeleted::class
    ];

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
     * Get the URL to this recording
     * 
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('recordings', $this);
    }

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
	public function toSearchableArray()
	{
		return [
            'category' => $this->category,
            'summary' => $this->summary,
            'title' => $this->title,
            'link' => $this->link
        ];
	}
	
	public function registerMediaConversions(Media $media = null)
	{
        $this->addMediaConversion('thumbnail')
             ->width(368)
             ->height(232)
             ->performOnCollections('videos');
	}
}
