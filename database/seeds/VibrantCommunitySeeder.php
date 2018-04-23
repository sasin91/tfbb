<?php

use App\Board;
use App\Comment;
use App\Diet;
use App\Profile;
use App\Reply;
use App\Thread;
use App\Workout;
use Facades\App\Scores\Popularity;
use Illuminate\Database\Seeder;

class VibrantCommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boards = factory(Board::class)->states('published')->times(10)->create();

        $boards->shuffle()->take(rand(4,8))->each(function ($board) {
        	$threads = factory(Thread::class)->times(rand(1,6))->create(['board_id' => $board->id]);

        	$threads->shuffle()->take(rand(2,4))->each(function ($thread) {
                if (Workout::count() > 0) {
                    $thread->creator->enrollWorkout(
                        Workout::all()->random()
                    );

                    // $thread->creator->enrollDiet(
                    //     Diet::all()->random()
                    // );
                }

                $profile = factory(Profile::class)->states(['filled', 'published'])->create(['creator_id' => $thread->creator->id]);
                $profile->addMedia(storage_path('Soldier_Yoga.jpg'))->preservingOriginal()->toMediaCollection('photos');
                $profile->addMedia(storage_path('Soldier_Yoga.ogv'))->preservingOriginal()->toMediaCollection('videos');

                $thread->addMedia(storage_path('1920x1080.png'))->preservingOriginal()->toMediaCollection('photos');

        		$posts = factory(Reply::class)->times(rand(1,10))->create(['thread_id' => $thread->id]);

        		$posts->shuffle()->take(rand(1,4))->each(function ($post) {
        			factory(Comment::class)->times(rand(1,2))->create([
        				'commentable_type' => Reply::class,
        				'commentable_id' => $post->id
        			]);
        		});
        	});
        });

        Popularity::forget('boards');
        $boards->shuffle()->take(rand(4,6))->each(function ($board) {
            Popularity::increment($board, rand(1,20));
        });
    }
}
