<?php

namespace App;

use App\Concerns\Finishable;
use App\Concerns\RoutesUsingHashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use Finishable, RoutesUsingHashid, SoftDeletes;

    protected $fillable = [
    	'hashid', 
    	'user_id',
    	'enrollable_type',
    	'enrollable_id',
    	'progress',
    	'finished_at'
    ];

    /**
     * The enrollable model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function enrollable()
    {
    	return $this->morphTo();
    }

    /**
     * Scoop enrolled workouts
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWorkouts($query) 
    {
        return $query->where('enrollable_type', Workout::class);
    } 

    /**
     * Scoop the enrolled Diets
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeDiets($query) 
    // {
    //     return $query->where('enrollable_type', Diet::class);
    // } 
}
