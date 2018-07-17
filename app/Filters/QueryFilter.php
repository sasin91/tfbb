<?php

namespace App\Filters;

use App\Contracts\FilterContract;
use App\Filters\Concerns\FiltersDates;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\Request;

abstract class QueryFilter implements FilterContract
{
    use Validatable, CallsRequestedQueryScopes;

    /**
     * The HTTP request containing the users requested filters.
     * 
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The Eloquent query.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Create a new QueryFilter instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->boot();
    }

    /**
     * Registered filters to operate upon.
     *
     * @var array
     * @return array
     */
    abstract public function filters();

    /**
     * Validation rules for the filters
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Initialize the query filter
     * 
     * @return void
     */
    public function boot()
    {
        $this->bootTraits();
    }

    /**
     * Boot all of the bootable traits on the query filter.
     *  
     * @return void
     */
    public function bootTraits()
    {
        foreach (class_uses_recursive($this) as $trait) {
            $method = 'boot'.class_basename($trait);

            if (method_exists($class, $method)) {
                $this->$method();
            }
        }
    }
}