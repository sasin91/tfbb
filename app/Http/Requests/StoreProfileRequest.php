<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravel\Spark\Spark;

class StoreProfileRequest extends FormRequest
{
    protected $requestedUser;

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
            if (filled($this->requestedUser()->profile)) {
                $validator->errors()->add('profile', __('User already have a profile.'));
            }
        });
    }

    /**
     * The User that has been requested a profile for.
     * 
     * @return \App\User | null
     */
    public function requestedUser()
    {
        if (is_null($this->input('user_id'))) {
            return $this->user();
        }

        if ($this->requestedUser) {
            return $this->requestedUser;
        }

        return $this->requestedUser = User::with('profile')->find($this->input('user_id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'nullable|integer|exists:users,id',
            'story' => 'required|string|min:5|max:65535|spamfree',
            'goals' => 'required|string|min:5|max:65535|spamfree',
            'training_level' => ['string', Rule::in(config('training.levels'))], 
            'training_style' => ['string', Rule::in(config('training.styles'))],
        ];
    }
}
