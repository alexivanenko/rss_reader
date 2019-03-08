<?php

namespace App\Feed;


class RssFeedReader
{

	/**
	 * @var \SimplePie
	 */
	private $reader;

	/**
	 * @param \SimplePie $reader
	 */
	public function setReader(\SimplePie $reader): void {
		$this->reader = $reader;
	}

	/**
	 * RssFeedReader constructor.
	 *
	 * @param string $url - RSS feed URL
	 */
	public function __construct($url)
	{
		$this->reader = \Feeds::make($url, 0, true);
	}

	/**
	 * @return \SimplePie_Item[]|null List of {@see \SimplePie_Item} objects
	 */
	public function getItems()
	{
		return $this->reader->get_items();
	}

	/**
	 * @return null|string
	 */
	public function getLink()
	{
		return $this->reader->get_permalink();
	}

	/**
	 * @return null|string
	 */
	public function getTitle()
	{
		return $this->reader->get_title();
	}

	/**
	 * Returns the list of most frequent words in the whole RSS feed
	 *
	 * @param array $exclude - the list of excluding words
	 * @param int $num - number of words
	 *
	 * @return array
	 */
	public function getMostFrequentWords($exclude = [], $num = 10)
	{
		$allWords = [];
		$items = $this->getItems();

		foreach ($items as $item) {
			$text = trim(strip_tags($item->get_description()));

			//remove numbers from text
			$text = preg_replace('/[0-9]+/', '', $text);
			//split text to words
			$words = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $text, -1, PREG_SPLIT_NO_EMPTY);

			if (sizeof($exclude) > 0) {
				//remove english most common words
				$words = array_filter($words, function ($item) use ($exclude){
					return ! in_array($item, $exclude);
				});
			}

			$allWords = array_merge($allWords, $words);
		}

		$frequency = array_count_values($allWords);
		arsort($frequency);

		return array_slice($frequency, 0, $num);
	}
}