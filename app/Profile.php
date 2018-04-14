<?php

namespace App;

use App\Concerns\Lockable;
use App\Concerns\Publishable;
use App\Events\Profile\ProfileCreated;
use App\Events\Profile\ProfileDeleted;
use App\Events\Profile\ProfileUpdated;
use Illuminate\Database\Eloquent\Model;
use Sasin91\LaravelVersionable\Versionable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as UsesMediaLibraryForFiles;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Profile extends Model implements HasMediaConversions
{
    use Publishable, Lockable, Versionable, UsesMediaLibraryForFiles;

    protected $fillable = [
        'published_at',
    	'creator_id',
    	'story',
    	'goals',
        'training_level',
        'training_style'
    ];

    protected $dates = ['published_at'];

    protected $appends = ['urls'];

    protected $dispatchesEvents = [
        'created' => ProfileCreated::class,
        'updated' => ProfileUpdated::class,
        'deleted' => ProfileDeleted::class
    ];

    /**
     * Get the urls to this profile.
     *     
     * @return array
     */
    public function getUrlsAttribute()
    {
        return [
            'web' => url('profiles', $this),
            'api' => route('profiles.show', $this),
            'publish' => route('profiles.publish', $this),
            'unpublish' => route('profiles.unpublish', $this)
        ];
    }

    /**
     * The user the profile belongs to.
     * 	
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator() 
    {
    	return $this->belongsTo(User::class);
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
    }
}
