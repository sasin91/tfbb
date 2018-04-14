<?php

namespace Tests;

trait WithoutSearchIndexing
{
	public function disableSearchIndexingForAllTests()
	{
		$this->app['config']->set('scout.driver', 'null');
	}
}