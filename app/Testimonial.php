<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
	protected $fillable = [
	 	'reviewer', 'reviewer_photo_url',
	 	'title', 'body'
	];
    /** 
     * Get the profile photo URL attribute.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getReviewerPhotoUrlAttribute($value)
    {
        return empty($value) ? 'https://www.gravatar.com/avatar/?s=200&d=mm' : url($value);
    }
}
