<?php

namespace Tests;

use App\Wiki\MostCommonWordsParser;
use Illuminate\Support\Facades\Cache;

class MostCommonWordsParserTest extends TestCase {

	public function testGetList() {
		$parser = new MostCommonWordsParser(false);
		$parser->setUrl(base_path('tests/data/most_common_words_wikipedia.html'));
		$parser->load();

		if (Cache::has($parser->getCacheKey()))
			Cache::delete($parser->getCacheKey());

		$actual = $parser->getList(5);
		$expected = [
			'the', 'be', 'to', 'of', 'and'
		];

		$this->assertEquals($expected, $actual);
	}
}
