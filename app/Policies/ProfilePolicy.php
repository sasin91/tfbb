<?php

namespace App\Policies;

use App\User;
use App\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization, BypassedByDevelopers;

    /**
     * Determine whether the user can upload photos to the profile.
     * 
     * @param  \App\User   $user   
     * @param  \App\Profile $profile 
     * @return bool         
     */
    public function uploadPhotos(User $user, Profile $profile)
    {
        return $profile->creator->is($user);
    }

    /**
     * Determine whether the user can upload videos to the profile.
     * 
     * @param  \App\User   $user   
     * @param  \App\Profile $profile 
     * @return bool         
     */
    public function uploadVideos(User $user, Profile $profile)
    {
        return $profile->creator->is($user);
    }

    /**
     * Determine whether the user can upload documents to the profile.
     * 
     * @param  \App\User   $user   
     * @param  \App\Profile $profile 
     * @return bool         
     */
    public function uploadDocuments(User $user, Profile $profile)
    {
        return $profile->creator->is($user);
    }
    
    /**
     * Determine whether the user can unpublish the profile.
     *     
     * @param  \App\User   $user   
     * @param  \App\Profile $profile 
     * @return boolean
     */
    public function unpublish(User $user, Profile $profile)
    {
        return $profile->creator->is($user);
    }

    /**
     * Determine whether the user can publish the profile.
     *     
     * @param  \App\User   $user   
     * @param  \App\Profile $profile 
     * @return boolean
     */
    public function publish(User $user, Profile $profile)
    {
        return $profile->creator->is($user);
    }

    /**
     * Determine whether the user can lock the profile.
     *     
     * @param  \App\User   $user   
     * @param  \App\Profile $profile 
     * @return boolean
     */
    public function lock(User $user, Profile $profile)
    {
        return $user->is_moderator;
    }

    /**
     * Determine whether the user can unlock the profile.
     *     
     * @param  \App\User   $user   
     * @param  \App\Profile $profile 
     * @return boolean
     */
    public function unlock(User $user, Profile $profile)
    {
        return $user->is_moderator;
    }

    /**
     * Determine whether the user can list the profiles
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
     * Determine whether the user can view the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function view(User $user, Profile $profile)
    {
        if ($user->isNotAModerator() && $user->isNotSubscribed()) {
            return false;
        }

        return $profile->isPublished()
            || $profile->creator->is($user);
    }

    /**
     * Determine whether the user can create profiles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->exists;
    }

    /**
     * Determine whether the user can update the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function update(User $user, Profile $profile)
    {
        return $user->is_moderator
            || $profile->creator->is($user);
    }

    /**
     * Determine whether the user can delete the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function delete(User $user, Profile $profile)
    {
        return $profile->creator->is($user);
    }
}
