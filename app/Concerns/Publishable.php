<?php

namespace App\Concerns;

trait Publishable
{
	/**
	 * Scoop the published models
	 * 	
	 * @param  \Illuminate\Database\Eloquent\Builder $query 
	 * @return \Illuminate\Database\Eloquent\Builder        
	 */
	public function scopePublished($query) 
	{
		return $query->withoutGlobalScope('unpublished')->whereNotNull('published_at');
	}
	
	/**
	 * Scoop the unpublished models
	 * 	
	 * @param  \Illuminate\Database\Eloquent\Builder $query 
	 * @return \Illuminate\Database\Eloquent\Builder        
	 */
	public function scopeUnpublished($query) 
	{
		return $query->withoutGlobalScope('published')->whereNull('published_at');
	} 

	/**
	 * Whether the model is published
	 * 
	 * @return bool 
	 */
	public function isPublished()
	{
		return filled($this->published_at);
	}

	/**
	 * Whether the model is not published
	 * 
	 * @return boolean 
	 */
	public function isUnpublished()
	{
		return blank($this->published_at);
	}

	/**
	 * Alias for isUnpublished
	 * 
	 * @return boolean 
	 */
	public function isNotPublished()
	{
		return $this->isUnpublished();
	}

	/**
	 * Publish the model
	 * 
	 * @param  \DateTime|string $atDate 
	 * @return $this
	 */
	public function publish($atDate = null)
	{
		$this->forceFill(['published_at' => $atDate ?? $this->freshTimestamp()])->saveOrFail();

		return $this;
	}

	/**
	 * Unpublish the model
	 * 
	 * @return $this
	 */
	public function unpublish()
	{
		$this->forceFill(['published_at' => null])->saveOrFail();

		return $this;
	}
}