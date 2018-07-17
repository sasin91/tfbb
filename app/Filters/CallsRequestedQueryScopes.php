<?php

namespace App\QueryScopes;

trait CallsRequestedQueryScopes
{
    /**
     * Apply the scopes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        $this->query = $query;

        foreach ($this->requestedQueryScopes() as $scope => $value) {
            $this->applyQueryScope($scope, $value);
        }

        return $this->query;
    }

    /**
     * Get the requested query scopes 
     * 
     * @return array
     */
    public function requestedQueryScopes()
    {
        return array_intersect_key(
            $this->request->input(), 
            array_flip($this->scopes())
        );
    }

    /**
     * scope value using scope.
     *
     * @param string $scope
     * @param mixed  $value
     *
     * @return void
     */
    private function applyQueryScope($scope, $value)
    {
        if (method_exists($this, $scope)) {
            $this->$scope($value);
        } elseif (camel_case($scope) != $scope) {
            $this->applyQueryScope(camel_case($scope), $value);
        }
    }
}