<?php

namespace App;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Parsedown;

class Markdown implements Htmlable
{
	/**
	 * The raw unprocessed text.
	 * 
	 * @var string
	 */
	public $text;

	/**
	 * Markdown Constructor.
	 * 
	 * @param string $text
	 */
	public function __construct($text)
	{
		$this->text = $text;
	}

	/**
	 * Parse given markdown string into valid html.
	 * 	
	 * @param  string $text 
	 * @return string       
	 */
	public static function parse($text)
	{
		return (new static($text))->toHtml();
	}

	/**
	 * Return the raw text when cast to a string.
	 * 	
	 * @return string 
	 */
	public function __toString()
	{
		return $this->raw();
	}

	/**
	 * Get the raw text
	 * 
	 * @return string
	 */
	public function raw()
	{
		return $this->text;
	}

	/**
	 * Get the parsed html result
	 * 
	 * @return string 
	 */
	public function html()
	{
		$parser = new Parsedown;

		return new HtmlString($parser->text($this->text));
	}

	/**
     * Get content as a string of HTML.
     *
     * @return string
     */
	public function toHtml()
	{
		return $this->html();
	}
}