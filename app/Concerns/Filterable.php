<?php

namespace App\Concerns;

use App\Contracts\Filter;
use Illuminate\Support\Arr;

trait Filterable
{
    /**
     * Eloquent scope for applying a filter.
     *
     * @param  \Illuminate\Database\Query\Builder       $query
     * @param  \App\Contracts\Filter[]                  $filters
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeFilter($query, $filters = null)
    {
        foreach (Arr::wrap($filters) as $filter) {
            $this->applyQueryFilter($query, $filter);
        }

        return $query;
    }

    /**
     * Apply a given filter to the Query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder    $query
     * @param  \App\Contracts\Filter                    $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyQueryFilter($query, $filter)
    {
        if ($filter instanceof Filter) {
            return $filter->apply($query);
        }

        if ($filter instanceof \Closure) {
            return $filter($query);
        }

        return $query;
    }
}
