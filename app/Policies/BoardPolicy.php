<?php

namespace App\Policies;

use App\Board;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardPolicy
{
    use HandlesAuthorization, BypassedByDevelopers;

    /**
     * Determine whether the user the list the boards.
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
     * Determine whether the user can view the board.
     *
     * @param  \App\User  $user
     * @param  \App\Board  $board
     * @return mixed
     */
    public function view(User $user, Board $board)
    {
        if (! $board->isPublished()) {
            return $user->is_moderator;
        }

        return $user->exists;
    }

    /**
     * Determine whether the user can create boards.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_moderator;
    }

    /**
     * Determine whether the user can update the board.
     *
     * @param  \App\User  $user
     * @param  \App\Board  $board
     * @return mixed
     */
    public function update(User $user, Board $board)
    {
        return $user->is_moderator;
    }

    /**
     * Determine whether the user can delete the board.
     *
     * @param  \App\User  $user
     * @param  \App\Board  $board
     * @return mixed
     */
    public function delete(User $user, Board $board)
    {
        return false;
    }
}
