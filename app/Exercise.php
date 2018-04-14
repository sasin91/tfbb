<?php

namespace App;

use App\Concerns\RoutesUsingSlug;
use App\Concerns\SyncsWithRemoteAPI;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Exercise extends Model implements HasMediaConversions
{
    use RoutesUsingSlug, SyncsWithRemoteAPI, Searchable, UsesMediaLibraryForFiles;

    protected $fillable = [
    	'provider', 'provider_id',
    	'name', 'slug',
    	'description',
    	'muscles', 'equipment'
    ];

    protected $casts = [
    	'muscles' => 'array',
    	'equipment' => 'array'
    ];

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
	}
}
