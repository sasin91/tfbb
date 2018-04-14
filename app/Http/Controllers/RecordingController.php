<?php

namespace App\Http\Controllers;

use App\Recording;
use Illuminate\Http\Request;

class RecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', new Recording);

        return view('recordings.index')->with('recordings', Recording::paginate());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function show(Recording $recording)
    {
        $this->authorize('view', $recording);

        return view('recordings.show')->with('recording', $recording->load('media'));
    }
}
