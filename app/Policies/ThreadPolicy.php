<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization, BypassedByDevelopers;

    /**
     * Determine whether the user can upload photos to the thread.
     * 
     * @param  \App\User   $user   
     * @param  \App\Thread $thread 
     * @return bool         
     */
    public function uploadPhotos(User $user, Thread $thread)
    {
        if ($thread->isLocked()) {
            return false;
        }

        return ($user->subscribed() && $thread->creator->is($user));
    }

    /**
     * Determine whether the user can reply to the thread.
     *     
     * @param  \App\User   $user   
     * @param  \App\Thread $thread 
     * @return bool         
     */
    public function reply(User $user, Thread $thread)
    {
        if ($thread->isLocked()) {
            return $user->is_moderator;
        }

        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can lock the thread.
     *     
     * @param  \App\User   $user   
     * @param  \App\Thread $thread 
     * @return boolean
     */
    public function lock(User $user, Thread $thread)
    {
        return $user->is_moderator;
    }

    /**
     * Determine whether the user can unlock the thread.
     *     
     * @param  \App\User   $user   
     * @param  \App\Thread $thread 
     * @return boolean
     */
    public function unlock(User $user, Thread $thread)
    {
        return $user->is_moderator;
    }

    /**
     * Determine whether the user can list the threads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can view the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function view(User $user, Thread $thread)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can create threads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Thread $thread)
    {
        if ($thread->isLocked()) {
            return $user->is_moderator;
        }

        return $user->is_moderator
            || ($user->subscribed() && $thread->creator->is($user));
    }

    /**
     * Determine whether the user can delete the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function delete(User $user, Thread $thread)
    {
        if ($thread->isLocked()) {
            return $user->is_moderator;
        }

        return $user->is_moderator
            || ($user->subscribed() && $thread->creator->is($user));
    }
}
