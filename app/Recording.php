<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Support\Fluent;
use Spatie\MediaLibrary\Media;
use App\Concerns\RoutesUsingSlug;
use Illuminate\Database\Eloquent\Model;
use App\Events\Recording\RecordingCreated;
use App\Events\Recording\RecordingDeleted;
use App\Events\Recording\RecordingUpdated;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\GeneratesSummaryByClampingBody;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;

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
	 * The URLs to this recording
	 * 
	 * @return \Illuminate\Support\Fluent
	 */
	public function getUrlsAttribute()
	{
		return new Fluent([
			'web' => url('recordings', $this),
			'api' => [
				'show' => route('recordings.show', $this),
				'update' => route('recordings.update', $this),
				'destroy' => route('recordings.destroy', $this)
			]
		]);
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
