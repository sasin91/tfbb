<?php

namespace App\Concerns;

trait Finishable
{
	/**
	 * Scoop the finished models
	 * 	
	 * @param  \Illuminate\Database\Eloquent\Builder $query 
	 * @return \Illuminate\Database\Eloquent\Builder        
	 */
	public function scopeFinished($query) 
	{
		return $query->withoutGlobalScope('unfinished')->whereNotNull('finished_at');
	}
	
	/**
	 * Scoop the unfinished models
	 * 	
	 * @param  \Illuminate\Database\Eloquent\Builder $query 
	 * @return \Illuminate\Database\Eloquent\Builder        
	 */
	public function scopeUnfinished($query) 
	{
		return $query->withoutGlobalScope('finished')->whereNull('finished_at');
	} 

	/**
	 * Determine if the model has finished
	 * 
	 * @return bool 
	 */
	public function isFinished()
	{
		return filled($this->finished_at);
	}

	/**
	 * Determine if the model is still in progress.
	 * 
	 * @return boolean 
	 */
	public function isUnfinished()
	{
		return blank($this->finished_at);
	}

	/**
	 * Alias for isUnfinished
	 * 
	 * @return boolean 
	 */
	public function isNotFinished()
	{
		return $this->isUnfinished();
	}

	/**
	 * Finish the model
	 * 
	 * @param  \DateTime|string $atDate 
	 * @return $this
	 */
	public function finish($atDate = null)
	{
		$this->forceFill(['finished_at' => $atDate ?? $this->freshTimestamp()])->saveOrFail();

		return $this;
	}

	/**
	 * Unfinish the model
	 * 
	 * @return $this
	 */
	public function unfinish()
	{
		$this->forceFill(['finished_at' => null])->saveOrFail();

		return $this;
	}
}