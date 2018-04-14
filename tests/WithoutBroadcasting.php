<?php

namespace Tests;

trait WithoutBroadcasting
{
	public function disableEventBroadcastingForAllTests()
	{
		$this->app['config']->set('broadcasting.default', 'null');
	}
}