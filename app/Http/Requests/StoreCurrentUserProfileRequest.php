<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCurrentUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (filled($this->user()->profile)) {
                $validator->errors()->add('profile', __('You already have a profile.'));
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'story' => 'required|string|min:5|max:65535|spamfree',
            'goals' => 'required|string|min:5|max:65535|spamfree',
            'training_level' => ['string', Rule::in(config('training.levels'))], 
            'training_style' => ['string', Rule::in(config('training.styles'))],
        ];
    }
}
