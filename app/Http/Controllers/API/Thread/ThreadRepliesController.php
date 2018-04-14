<?php

namespace App\Http\Controllers\API\Thread;

use App\Http\Controllers\Controller;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ThreadRepliesController extends Controller
{
	public function index(Thread $thread)
	{
        $this->authorize('index', new Reply);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer'
        ]);

        return $thread->replies()->with(['creator', 'creator.profile'])->paginate(
            request('perPage'), 
            ['*'],
            request('pageName'),
            request('page')
        );
	}

	public function store(Request $request, Thread $thread)
	{
		$this->authorize('reply', $thread);

		$this->validate($request, [
            'title' => 'nullable|string|max:50|spamfree',
            'body' => 'required|string|min:5|max:255|spamfree'
		]);

		return $thread->replies()->create([
			'creator_id' => $request->user()->id,
			'title' => $request->input('title'),
			'body' => $request->input('body')
		]);
	}
}