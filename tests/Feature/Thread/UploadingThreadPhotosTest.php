<?php

namespace Tests\Feature\Thread;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutQueue;
use Tests\WithoutSearchIndexing;

class UploadingThreadPhotosTest extends TestCase
{
	use RefreshDatabase, WithoutQueue, WithoutBroadcasting, WithoutSearchIndexing;

	public function cannotUploadPhotos()
	{
		return [
			['asGuest', false, 401],
			['asGuest', true, 401],
			['asUser', false, 403],
			['asUser', true, 403],
			['asSubscriber', false, 403],
			['asSubscriber', true, 403],
			['asModerator', false, 403],
			['asModerator', true, 403]
		];
	}

	public function acceptedPhotoDimensions()
	{
		return [
			['320', '240'],
			// ...
			['2160', '4096']
		];
	}

	/** @test */
	function cannot_upload_photos_at_less_than_320x240() 
	{
		$thread = factory(Thread::class)->create();

		$this->asDev()->json('POST', "/api/threads/{$thread->hashid}/photos", [
			'photo' => UploadedFile::fake()->image('photo.jpg', 310, 230)
		])->assertJsonValidationErrors('photo')->assertSee(__('validation.dimensions'));
	}

	/** @test */
	function cannot_upload_photos_greater_than_2160x4096() 
	{
		$thread = factory(Thread::class)->create();

		$this->asDev()->json('POST', "/api/threads/{$thread->hashid}/photos", [
			'photo' => UploadedFile::fake()->image('photo.jpg', 2161, 4097)
		])->assertJsonValidationErrors('photo')->assertSee(__('validation.dimensions'));
	} 

	/** 
	 * @test
	 * @dataProvider cannotUploadPhotos          
	 */
	function cannot_upload_photos_to_a_thread_they_did_not_create($asRole, $locked, $expectedStatus) 
	{
		Storage::fake();

		$thread = factory(Thread::class)->create(['locked_at' => $locked ? now() : null]);

		$this->$asRole()->json('POST', "/api/threads/{$thread->hashid}/photos", [
			'photo' => UploadedFile::fake()->image('photo.jpg', 320, 240)
		])->assertStatus($expectedStatus);

		$this->assertNull($thread->getMedia('photos')->first());
	} 

	/** @test */
	function creator_can_upload_photo_to_their_thread() 
	{
		Storage::fake();

		$user = factory(User::class)->create();

		$thread = factory(Thread::class)->create(['creator_id' => $user->id]);

		$this->asSubscriber($user)->json('POST', "/api/threads/{$thread->hashid}/photos", [
			'photo' => UploadedFile::fake()->image('photo.jpg', 320, 240)
		])->assertSuccessful();

		$this->assertNotNull($thread->getMedia('photos')->first());
		$this->assertEquals('photo', $thread->getMedia('photos')->first()->name);
	}

	/** @test */
	function creator_cannot_upload_photos_to_their_thread_when_its_locked() 
	{
		Storage::fake();

		$user = factory(User::class)->create();

		$thread = factory(Thread::class)->states('locked')->create(['creator_id' => $user->id]);

		$this->asSubscriber($user)->json('POST', "/api/threads/{$thread->hashid}/photos", [
			'photo' => UploadedFile::fake()->image('photo.jpg', 320, 240)
		])->assertStatus(403);

		$this->assertNull($thread->getMedia('photos')->first());
	}

	/** @test */
	function developer_can_upload_photos_to_a_thread_they_did_not_create() 
	{
		Storage::fake();

		$thread = factory(Thread::class)->create();

		$this->asDev()->json('POST', "/api/threads/{$thread->hashid}/photos", [
			'photo' => UploadedFile::fake()->image('photo.jpg', 320, 240)
		])->assertSuccessful();

		$this->assertNotNull($thread->getMedia('photos')->first());
		$this->assertEquals('photo', $thread->getMedia('photos')->first()->name);
	}

	/** @test */
	function developer_can_upload_photos_to_a_locked_thread_they_did_not_create() 
	{
		Storage::fake();

		$thread = factory(Thread::class)->states('locked')->create();

		$this->asDev()->json('POST', "/api/threads/{$thread->hashid}/photos", [
			'photo' => UploadedFile::fake()->image('photo.jpg', 320, 240)
		])->assertSuccessful();

		$this->assertNotNull($thread->getMedia('photos')->first());
		$this->assertEquals('photo', $thread->getMedia('photos')->first()->name);
	}
}
