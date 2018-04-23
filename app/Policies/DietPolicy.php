<?php

namespace App\Policies;

use App\User;
use App\Diet;
use Illuminate\Auth\Access\HandlesAuthorization;

class DietPolicy
{
    use HandlesAuthorization, BypassedByDevelopers;

    /**
     * Determine whether the user can upload photos to the diet.
     * 
     * @param  \App\User   $user   
     * @param  \App\Diet $diet 
     * @return bool         
     */
    public function uploadPhotos(User $user, Diet $diet)
    {
        return false;
    }

    /**
     * Determine whether the user can upload videos to the diet.
     * 
     * @param  \App\User   $user   
     * @param  \App\Diet $diet 
     * @return bool         
     */
    public function uploadVideos(User $user, Diet $diet)
    {
        return false;
    }

    /**
     * Determine whether the user can upload documents to the diet.
     * 
     * @param  \App\User   $user   
     * @param  \App\Diet $diet 
     * @return bool         
     */
    public function uploadDocuments(User $user, Diet $diet)
    {
        return false;
    }

    /**
     * Determine whether the user search within diets.
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
     * Determine whether the user can view a listing of diets.
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
     * Determine whether the user can view the diet.
     *
     * @param  \App\User  $user
     * @param  \App\Diet  $diet
     * @return mixed
     */
    public function view(User $user, Diet $diet)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can create diets.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the diet.
     *
     * @param  \App\User  $user
     * @param  \App\Diet  $diet
     * @return mixed
     */
    public function update(User $user, Diet $diet)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the diet.
     *
     * @param  \App\User  $user
     * @param  \App\Diet  $diet
     * @return mixed
     */
    public function delete(User $user, Diet $diet)
    {
        return false;
    }
}
