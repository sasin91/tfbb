<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait Lockable
{
    // public static function bootLockable()
    // {
    // 	static::addGlobalScope('onlyUnlocked', function ($query) {
    // 		$query->whereNull('locked_at');
    // 	});
    // }

    /**
     * Scoop the locked models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeLocked($query) 
    {
        return $query->withoutGlobalScope('unlocked')->whereNotNull('locked_at');
    }

    /**
     * Scoop the models that are not locked.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeUnlocked($query) 
    {
        return $query->withoutGlobalScope('locked')->whereNull('locked_at');
    }

    /**
     * Whether the model is locked.
     * 
     * @return bool
     */
    public function isLocked()
    {
        return filled($this->locked_at);
    }

    /**
     * Lock the model.
     * 
     * @return $this
     */
    public function lock($atDate = null, $reason = null)
    {
        $this->forceFill([
            'locked_at' => $atDate ?? $this->freshTimestamp(),
            'locked_because' => $reason
        ])->saveOrFail();

        return $this;
    }

    /**
     * Unlock the model.
     * 
     * @return $this
     */
    public function unlock()
    {
        $this->forceFill(['locked_at' => null, 'locked_because' => null])->saveOrFail();

        return $this;
    }
}
