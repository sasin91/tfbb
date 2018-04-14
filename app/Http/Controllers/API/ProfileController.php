<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', new Profile);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer'
        ]);

        return Profile::published()->paginate(
            request('perPage'), 
            ['*'],
            request('pageName'),
            request('page')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProfileRequest $request)
    {
        $this->authorize('create', new Profile);

        return $request->requestedUser()->profile()->create(
            request(['story', 'goals', 'training_level', 'training_style'])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        $this->authorize('view', $profile);

        return $profile->load('creator');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $this->authorize('update', $profile);

        $this->validate(request(), [
            'story' => 'string|min:5|max:65535|spamfree',
            'goals' => 'string|min:5|max:65535|spamfree',
            'training_level' => ['string', Rule::in(config('training.levels'))], 
            'training_style' => ['string', Rule::in(config('training.styles'))],
        ]);

        return tap($profile)->update($request->only(['story', 'goals', 'training_level', 'training_style']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        $this->authorize('delete', $profile);

        $profile->delete();

        return response(['deleted' => true], 200);
    }
}
