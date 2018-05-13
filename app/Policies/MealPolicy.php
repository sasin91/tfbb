<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
{
    use HandlesAuthorization, BypassedByDevelopers;

    /**
     * Determine whether the user can attach foods to the meal.
     *         
     * @param  \App\User   $user 
     * @return bool       
     */
    public function attachFoods(User $user, Meal $meal)
    {
        return false;
    }

    /**
     * Determine whether the user can view a listing of meals.
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
     * Determine whether the user can view the meal.
     *
     * @param  \App\User  $user
     * @param  \App\Meal  $meal
     * @return mixed
     */
    public function view(User $user, Meal $meal)
    {
        return $user->is_moderator
            || $user->subscribed();
    }

    /**
     * Determine whether the user can create meals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the meal.
     *
     * @param  \App\User  $user
     * @param  \App\Meal  $meal
     * @return mixed
     */
    public function update(User $user, Meal $meal)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the meal.
     *
     * @param  \App\User  $user
     * @param  \App\Meal  $meal
     * @return mixed
     */
    public function delete(User $user, Meal $meal)
    {
        return false;
    }
}
