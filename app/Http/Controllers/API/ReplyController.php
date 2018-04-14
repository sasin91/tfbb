<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function update(Reply $reply)
	{
		$this->authorize('update', $reply);

		$this->valiate(request(), [
            'title' => 'nullable|string|max:50|spamfree',
            'body' => 'string|min:5|max:255|spamfree'
		]);

		return tap($reply)->update(
			$request->only(['title', 'body'])
		);
	}

	public function destroy(Reply $reply)
	{
		$this->authorize('delete', $reply);

		$reply->delete();

		return response()->json(['deleted' => true], 200);
	}
}
