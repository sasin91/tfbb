<?php

namespace App\Concerns;

trait RoutesUsingSlug
{
    /**
     * Build a slug if none exists when the model is being created.
     *
     * @return void
     */
    public static function bootRoutesUsingSlug()
    {
        static::creating(function ($model) {
            $model->refreshSlugIfNeeded();
        });

        static::updating(function ($model) {
        	$model->refreshSlugIfNeeded();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The key used for building the slug.
     *
     * @return string
     */
    public function sluggable()
    {
        return 'name';
    }

    /**
     * Refresh slug attribute if needed.
     * 
     * @return $this
     */
    public function refreshSlugIfNeeded()
    {
        if ($this->slugShouldRefresh()) {
            $this->forceFill([
                'slug' => $this->buildSlug()
            ]);
        }

        return $this;
    }

    /**
     * Build a slug from the sluggable key.
     *
     * @return string
     */
    public function buildSlug()
    {
        $sluggable = $this->{$this->sluggable()};

        return str_slug($sluggable);
    }

    /**
     * Determine whether the slug should be built and kept in sync with the sluggable attribute.
     * 
     * @return bool
     */
    public function buildsSlugFromSluggable()
    {
        return true;
    }

    /**
     * Determine whether the slug should be refreshed
     *
     * @return boolean
     */
    public function slugShouldRefresh()
    {
        // If the key we build our slug from is blank,
        // there is really nothing we'll assume no slug should be built.
        if (blank($this->{$this->sluggable()})) {
            return false;
        }

        if (blank($this->getRouteKey())) {
            return true;
        }

        if (! $this->buildsSlugFromSluggable()) {
            return false;
        }

        return $this->isDirty($this->sluggable())
            && $this->getRouteKey() !== $this->buildSlug();
    }
}
