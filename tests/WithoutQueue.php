<?php

namespace Tests;

trait WithoutQueue
{
	public function disableQueueForAllTests()
	{
		$this->app['config']->set('queue.default', 'null');
	}
}