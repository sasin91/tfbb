<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordingPolicy
{
    use HandlesAuthorization, BypassedByDevelopers;

    /**
     * Determine whether the user can upload videos to the recording.
     * 
     * @param  \App\User   $user   
     * @param  \App\Recording $recording 
     * @return bool         
     */
    public function uploadVideos(User $user, Recording $recording)
    {
        return false;
    }

    /**
     * Determine whether the user can view a listing of recordings.
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
     * Determine whether the user can view the recording.
     *
     * @param  \App\User  $user
     * @param  \App\Recording  $recording
     * @return mixed
     */
    public function view(User $user, Recording $recording)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can create recordings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the recording.
     *
     * @param  \App\User  $user
     * @param  \App\Recording  $recording
     * @return mixed
     */
    public function update(User $user, Recording $recording)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the recording.
     *
     * @param  \App\User  $user
     * @param  \App\Recording  $recording
     * @return mixed
     */
    public function delete(User $user, Recording $recording)
    {
        return false;
    }
}
