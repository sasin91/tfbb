<?php

namespace App\Filters;

use Illuminate\Foundation\Http\ValidationFactory;

trait Validatable 
{
    /**
     * Validate the filters
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     *
     * @throws \Illuminate\Validation\ValidationException
     * @return \Illuminate\Validation\Validator
     */
    public function validate($factory = null)
    {
        return tap($this->validator($factory), function ($validator) {
            $validator->validate();
        });
    }

    /**
     * Add our rules to a given or new validation factory instance.
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     * @return \Illuminate\Validation\Validator
     */
    public function validator($factory = null)
    {
        if (is_null($factory)) {
            $factory = resolve(ValidationFactory::class);
        } 

        return $factory->make(
            $this->request->all(),
            $this->rules()
        );
    }
}