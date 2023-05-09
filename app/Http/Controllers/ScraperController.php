<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ScraperController extends Controller
{
    private $results = array();

    public function scraper()
    {
        $client = new Client();
        $res = $client->request('GET', 'https://bonbast.com/archive');

        $crawler = new Crawler((string)$res->getBody());
        $crawler->filter('.table ')->each(function ($item) {
            $item->filter('tr')->each(function ($tr){
                $tr->filter('td')->each(function ($td){
                    print_r($td->text());
                    print_r(" ");

                }) ;
            }) ;
        });

    }
}
