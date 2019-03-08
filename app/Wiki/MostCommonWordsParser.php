<?php
namespace App\Wiki;


use Illuminate\Support\Facades\Cache;
use PHPHtmlParser\Dom;

class MostCommonWordsParser
{

	/**
	 * @var Dom
	 */
	private $dom;

	/**
	 * @var string
	 */
	private $cacheKey = 'most_common_words';

	/**
	 * @var string
	 */
	private $url = 'https://en.wikipedia.org/wiki/Most_common_words_in_English';

	/**
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getCacheKey() {
		return $this->cacheKey;
	}

	/**
	 * MostCommonWordsParser constructor.
	 *
	 * @param bool $load - load HTML to the Dom object
	 */
	public function __construct($load = true)
	{
		$this->dom = new Dom();

		if ($load)
			$this->load();
	}

	/**
	 * Load HTML
	 */
	public function load()
	{
		$this->dom->load($this->url);
	}

	/**
	 * Returns the list of most common english words
	 *
	 * @param int $num - number of words (max 100)
	 *
	 * @return array
	 */
	public function getList($num = 50)
	{
		$max = $num <= 100 ? $num : 100;

		if (Cache::has($this->cacheKey)) {
			$list = Cache::get($this->cacheKey);

			if (sizeof($list) == $max) {
				$result = $list;
			} elseif (sizeof($list) > $max) {
				$result = array_slice($list, 0, $max);
			} else {
				$result = $this->fillList($max);
				Cache::put($this->cacheKey, $result);
			}

		} else {
			$result = $this->fillList($max);
			Cache::put($this->cacheKey, $result);
		}

		return $result;
	}

	/**
	 * Fill list with most common english words
	 *
	 * @param int $max - max number of words in list
	 *
	 * @return array
	 */
	private function fillList($max)
	{
		$result = [];
		$content = $this->parse();

		if (! $content)
			return $result;

		foreach ($content as $item) {
			$result[] = $item->innerHtml;

			if (sizeof($result) == $max) {
				break;
			}
		}

		return $result;
	}

	/**
	 * Parse Wiki page
	 *
	 * @return mixed
	 */
	private function parse()
	{
		$content = $this->dom->find('table.wikitable', 0);

		if ($content)
			$content = $content->find('a.extiw');

		return $content;
	}
}