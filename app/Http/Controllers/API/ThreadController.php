<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);

        $this->validate($request, [
            'title' => 'string|min:5|max:50|spamfree',
            'body' => 'string|min:5|max:50|spamfree'
        ]);

        return tap($thread)->update(
            $request->only(['title', 'body'])
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return response(['deleted' => true]);
    }
}
