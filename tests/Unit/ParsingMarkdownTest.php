<?php

namespace Tests\Unit;

use App\Concerns\ParsesMarkdown;
use App\Markdown;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParsingMarkdownTest extends TestCase
{
	public function markdownToHtml()
	{
		return [
			[
				'# hello world', '<h1>hello world</h1>',
			],
			[
				'## hello', '<h2>hello</h2>', 
			]
		];
	}

	/** 
	 * @test
	 * @dataProvider markdownToHtml
	 */
	function it_parses_markdown_to_valid_html($text, $html) 
	{
		$this->assertEquals($html, (new Markdown($text))->html());
	} 
}
