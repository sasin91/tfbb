<?php

namespace App\Concerns\User;

trait OverridenRelationMethods
{
    /**
     * Load the current diet model from the relation
     * 
     * @return \App\Diet | null
     */
    public function getCurrentDietAttribute()
    {
        if ($this->relationLoaded('currentDiet')) {
            return $this->getRelation('currentDiet');
        }

        return tap($this->currentDiet()->first(), function ($diet) {
            if ($diet) {
                $this->setRelation('currentDiet', $diet);
            }
        });
    }

    /**
     * Load the current workout model from the relation
     * 
     * @return \App\Workout | null
     */
    public function getCurrentWorkoutAttribute()
    {
        if ($this->relationLoaded('currentWorkout')) {
            return $this->getRelation('currentWorkout');
        }

        return tap($this->currentWorkout()->first(), function ($workout) {
            if ($workout) {
                $this->setRelation('currentWorkout', $workout);
            }
        });
    }
}