<?php

namespace App;

use App\Concerns\Commentable;
use App\Concerns\GeneratesSummaryByClampingBody;
use App\Concerns\RoutesUsingSlug;
use App\Events\Workout\WorkoutCreated;
use App\Events\Workout\WorkoutDeleted;
use App\Events\Workout\WorkoutUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Workout extends Model implements HasMediaConversions
{
	use 
		RoutesUsingSlug,
	 	Commentable, 
	 	GeneratesSummaryByClampingBody, 
	 	Searchable, 
	 	SoftDeletes, 
	 	UsesMediaLibraryForFiles;

    protected $fillable = [
    	'title', 'slug',
    	'level', 'type',
    	'summary', 'body'
    ];

    protected $appends = ['link'];

    protected $dispatchesEvents = [
        'created' => WorkoutCreated::class,
        'updated' => WorkoutUpdated::class,
        'deleted' => WorkoutDeleted::class
    ];

    /**
     * Get the URL to this workout
     * 
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('workouts', $this);
    }

    /**
     * A Workout schedule consists of exercises spread over a period
     *     
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class);
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
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'summary' => $this->summary,
            'level' => $this->level,
            'type' => $this->type,
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
            'title' => $this->title,
            'link' => $this->link
        ];
    }

	public function registerMediaConversions(Media $media = null)
	{
		$this->addMediaConversion('thumbnail')
		     ->width(368)
		     ->height(232)
		     ->performOnCollections('photos');

		$this->addMediaConversion('thumbnail')
		     ->width(368)
		     ->height(232)
		     ->performOnCollections('videos');

		$this->addMediaConversion('thumbnail')
		     ->width(368)
		     ->height(232)
		     ->performOnCollections('documents');
	}
}
