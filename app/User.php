<?php

namespace App;

use App\Concerns\User\OverridenRelationMethods;
use App\Concerns\User\RefreshesAge;
use App\Events\User\UserCreated;
use App\Events\User\UserUpdated;
use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Spark\CanJoinTeams;
use Laravel\Spark\User as SparkUser;
use Sasin91\LaravelVersionable\Versionable;

class User extends SparkUser
{    
    use CanJoinTeams, OverridenRelationMethods, RefreshesAge, Versionable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'last_seen_at',
        'gender',
        'weight',
        'age',
        'born_at',
        'is_moderator',
        'current_workout_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'two_factor_reset_code',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_seen_at' => 'datetime',
        'born_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_moderator' => 'boolean',
        'uses_two_factor_auth' => 'boolean',
    ];

    /**
    * The event map for the model.
    *
    * Allows for object-based events for native Eloquent events.
    *
    * @var array
    */
    protected $dispatchesEvents = [
        'created' => UserCreated::class,
        'updated' => UserUpdated::class 
    ];

    /**
     * Determine whether the user is a moderator.
     * 
     * @return boolean 
     */
    public function isModerator()
    {
        return $this->getAttribute('is_moderator');
    }

    /**
     * Determine if the user is not a moderator.
     *     
     * @return boolean 
     */
    public function isNotAModerator()
    {
        return ! $this->isModerator();
    }

    /**
     * Determine if the user is not subscribed.
     * 
     * @return boolean 
     */
    public function isNotSubscribed()
    {
        return ! $this->subscribed();
    }

    /**
     * Get the versionable data array for the model.
     *
     * @return array
     */
    public function toVersionableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'weight' => $this->weight,
            'is_moderator' => $this->is_moderator,
            'current_workout_id' => optional($this->currentWorkout)->id,
            'current_diet_id' => optional($this->currentDiet)->id,
        ];
    }
    
    /**
     * The users profile.
     *     
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'creator_id');
    }   

    /**
     * The enrollments the user is currently subscribed to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments() 
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the enrolled models.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function enrolled()
    {
        return $this->morphMany(Enrollment::class, 'enrollable')->where('user_id', $this->id);
    }

    /**
     * A user may have followed multiple workouts.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function workouts()
    {
        return $this
            ->hasManyThrough(Workout::class, Enrollment::class, 'user_id', 'id', 'id', 'enrollable_id')
            ->where('enrollable_type', Workout::class);
    }

    /**
     * Enroll given workout.
     *     
     * @param  Workout $workout 
     * @return Enrollment
     */
    public function enrollWorkout(Workout $workout)
    {
        return $workout->enroll($this);

        // return $this->enrollments()->create([
        //     'enrollable_id' => $workout->id,
        //     'enrollable_type' => Relation::getMorphedModel(Workout::class) ?? Workout::class
        // ]);
    }

    /**
     * The workout we're currently on
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo | Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function currentWorkout()
    {
        return $this->workouts()->latest()->take(1);
    }

    /**
     * A user may have followed multiple diets.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function diets()
    {
        return $this
            ->hasManyThrough(Diet::class, Enrollment::class, 'user_id', 'id', 'id', 'enrollable_id')
            ->where('enrollable_type', Diet::class);
    }

    /**
     * Enroll given diet.
     *     
     * @param  Diet $diet 
     * @return Enrollment
     */
    public function enrollDiet(Diet $diet)
    {
        return $diet->enroll($this);

        // return $this->enrollments()->create([
        //     'enrollable_id' => $diet->id,
        //     'enrollable_type' => Relation::getMorphedModel(Diet::class) ?? Diet::class
        // ]);
    }


    /**
     * The diet the user is currently follwing.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo | Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function currentDiet()
    {
        return $this->diets()->latest()->take(1);
    }
}
