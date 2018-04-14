<?php

namespace App\Policies;

use App\User;
use App\Workout;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkoutPolicy
{
    use HandlesAuthorization, BypassedByDevelopers;

    /**
     * Determine whether the user can select the workout for the month.
     *     
     * @param  \App\User    $user    
     * @param  \App\Workout $workout 
     * @return bool           
     */
    public function selectAsWorkoutOfTheMonth(User $user, Workout $workout)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can upload photos to the workout.
     * 
     * @param  \App\User   $user   
     * @param  \App\Workout $workout 
     * @return bool         
     */
    public function uploadPhotos(User $user, Workout $workout)
    {
        return false;
    }

    /**
     * Determine whether the user can upload videos to the workout.
     * 
     * @param  \App\User   $user   
     * @param  \App\Workout $workout 
     * @return bool         
     */
    public function uploadVideos(User $user, Workout $workout)
    {
        return false;
    }

    /**
     * Determine whether the user can upload documents to the workout.
     * 
     * @param  \App\User   $user   
     * @param  \App\Workout $workout 
     * @return bool         
     */
    public function uploadDocuments(User $user, Workout $workout)
    {
        return false;
    }

    /**
     * Determine whether the user search within workouts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function search(User $user)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can view a listing of workouts.
     *         
     * @param  \App\User   $user 
     * @return bool       
     */
    public function index(User $user)
    {
        return $user->is_moderator
            || $user->subscribed();
    }   

    /**
     * Determine whether the user can view the workout.
     *
     * @param  \App\User  $user
     * @param  \App\Workout  $workout
     * @return mixed
     */
    public function view(User $user, Workout $workout)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can create workouts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the workout.
     *
     * @param  \App\User  $user
     * @param  \App\Workout  $workout
     * @return mixed
     */
    public function update(User $user, Workout $workout)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the workout.
     *
     * @param  \App\User  $user
     * @param  \App\Workout  $workout
     * @return mixed
     */
    public function delete(User $user, Workout $workout)
    {
        return false;
    }
}
