<?php

namespace App\Http\Controllers;

use App\Feed\RssFeedReader;
use App\Wiki\MostCommonWordsParser;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$feed = new RssFeedReader(env('RSS_FEED'));
		$mostCommonWordsParser = new MostCommonWordsParser();

	    return view('home', [
	    	'key_words' => $feed->getMostFrequentWords($mostCommonWordsParser->getList()),
	    	'rss' => [
	            'title' => $feed->getTitle(),
		        'link' => $feed->getLink(),
		        'items' => $feed->getItems()
		    ]
        ]);
    }
}
