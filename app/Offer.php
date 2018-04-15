<?php

namespace App;

use App\Concerns\RoutesUsingSlug;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;
use Watson\Rememberable\Rememberable;

class Offer extends Model
{
	use RoutesUsingSlug, Rememberable, Searchable, UsesMediaLibraryForFiles;

    protected $fillable = [
    	'name','slug',
    	'tagline',
    	'discount', 'body',
    	'poster_url', 'banner_url',
    	'offsite_link',

        'view'
    ];

    /**
     * Get the link to this offer.
     * 
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('offers', $this);
    }

    /**
     * The testimonials of the offer
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testimonials()
    {
    	return $this->belongsToMany(Testimonial::class);
    }

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
	public function toSearchableArray()
	{
		return [
        	'name' => $this->getAttribute('name'),
        	'summary' => $this->getAttribute('summary'),
        	'poster_url' => $this->getAttribute('poster_url'),
        	'link' => $this->getAttribute('link')
        ];
	}
}
