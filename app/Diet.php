<?php

namespace App;

use App\Concerns\Enrollable;
use App\Concerns\RoutesUsingSlug;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Diet extends Model implements HasMediaConversions
{
	use Enrollable, RoutesUsingSlug, Searchable, UsesMediaLibraryForFiles;

	protected $fillable = [
		'goal', 'style',
		'title', 'slug',
		'summary', 'body',
		'view',
        'banner_url'
	];

    /**
     * The meals the diet offers
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'dishes')->using(Dish::class);
    }

    /**
     * Get the banner URL or default to first uploaded photo.
     * 
     * @param  string | null $url 
     * @return string      
     */
    public function getBannerUrlAttribute($url)
    {
        return $url ?? optional($this->getFirstMedia('photos'))->getUrl('thumbnail');
    }

	/**
	 * Get the view name of the diet.
	 * 
	 * @param  string|null $value 
	 * @return string        
	 */
	public function getViewAttribute($value)
	{
		return $value ?? 'diets.generic';
	}

    public function getUrlsAttribute()
    {
        return (object)[
            'web' => url('diets', $this),
            'api' => (object)[
                'files' => (object)[
                    'index' => route('diets.files.index', $this),
                    'store' => route('diets.files.store', $this) 
                ],
                'store' => route('diets.store'),
                'show' => route('diets.show', $this),
                'update' => route('diets.update', $this),
                'destroy' => route('diets.destroy', $this)
            ]
        ];
    }

    /**
     * Get the URL to this diet
     * 
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('diets', $this);
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
            'goal' => $this->goal,
            'style' => $this->style,
            'link' => $this->link,
            'meals' => $this->meals->count(),
            'calories' => $this->meals->sum('total_calories')
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
