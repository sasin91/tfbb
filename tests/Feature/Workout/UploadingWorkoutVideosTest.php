<?php

namespace Tests\Feature\Workout;

use App\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutQueue;
use Tests\WithoutSearchIndexing;

class UploadingWorkoutVideosTest extends TestCase
{
	use RefreshDatabase, WithoutQueue, WithoutBroadcasting, WithoutSearchIndexing;


    public function cannotUploadVideos()
    {
    	return [
    		['asGuest', 401],
    		['asUser', 403],
    		['asModerator', 403]
    	];
    }

    public function canUploadVideos()
    {
    	return [
    		['asDev']
    	];
    }

    /** 
     * @test
     * @dataProvider cannotUploadVideos
     */
    function cannot_upload_videos($asRole, $expectedStatus) 
    {
    	Storage::fake();

    	$workout = factory(Workout::class)->create();

    	$this->$asRole()->json('POST', "/api/workouts/{$workout->slug}/videos", [
    		'videos' => [UploadedFile::fake()->create('video.mp4', 450)]
    	])->assertStatus($expectedStatus);

		$this->assertNull($workout->getMedia('videos')->first());
    }

    /** 
     * @test
     * @dataProvider canUploadVideos
     */
    function can_upload_videos($asRole) 
    {
    	Storage::fake();

    	$workout = factory(Workout::class)->create();

    	$this->$asRole()->json('POST', "/api/workouts/{$workout->slug}/videos", [
    		'videos' => [UploadedFile::fake()->create('deadlifting-101.mp4', 450)]
    	])->assertSuccessful();

    	$this->assertEquals('deadlifting-101', $workout->getFirstMedia('videos')->name);
    } 
}