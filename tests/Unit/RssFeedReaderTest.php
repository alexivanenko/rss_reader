<?php

namespace Tests\Unit;

use App\Feed\RssFeedReader;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class RssFeedReaderTest extends TestCase {

	/**
	 * @var MockObject
	 */
	private $reader;

	/**
	 * @var RssFeedReader
	 */
	private $feed;

	public function setUp(): void {
		parent::setUp();

		$this->reader = $this->getMockBuilder(\SimplePie::class)
			->setMethods(['get_items'])
			->getMock();

		$this->feed = new RssFeedReader(env('RSS_FEED'));
	}

	public function testGetMostFrequentWords() {
		$list = [
			new class {
				public function get_description() {
					return "one two one three four";
				}
			}
		];

		$this->reader->expects(self::once())->method('get_items')->willReturn($list);

		$this->feed->setReader($this->reader);
		$actual = $this->feed->getMostFrequentWords([], 3);

		$expected = [
			'one' => 2,
			'two' => 1,
			'three' => 1
		];

		$this->assertEquals($expected, $actual);
	}

	public function testGetMostFrequentWordsGarbage() {
		$list = [
			new class {
				public function get_description() {
					return "on'e two 2019 one, 323, - ,three' ,'four";
				}
			}
		];

		$this->reader->expects(self::once())->method('get_items')->willReturn($list);

		$this->feed->setReader($this->reader);
		$actual = $this->feed->getMostFrequentWords([], 3);

		$expected = [
			"on'e" => 1,
			'two' => 1,
			'one' => 1
		];

		$this->assertEquals($expected, $actual);
	}

	public function testGetMostFrequentWordsExclude() {
		$list = [
			new class {
				public function get_description() {
					return "one two one four three four four";
				}
			}
		];

		$this->reader->expects(self::once())->method('get_items')->willReturn($list);

		$this->feed->setReader($this->reader);
		$actual = $this->feed->getMostFrequentWords(['two', 'three'], 10);

		$expected = [
			'one' => 2,
			'four' => 3
		];

		$this->assertEquals($expected, $actual);
	}
}
