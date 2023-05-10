<?php

namespace App\Http\Controllers;


use App\Http\Resources\BotResource;
use App\Models\Bot;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponse;

class ScraperController extends Controller
{
    use ApiResponse;

    private $results = array();

    public function scraper(Request $request)
    {

        $client = new Client();
        // $res = $client->request('GET', 'https://bonbast.com/archive');
        //Storage::disk('local')->put('res.txt', (string)$res->getBody());
        //$crawler = new Crawler((string)$res->getBody());


        $res = Storage::disk('local')->get('res.txt');
        $crawler = new Crawler($res);


        $crawler->filter('.table ')->each(function ($item) {
            $item->filter('tr')->each(function ($tr) {
                $tr->filter('td')->each(function ($td) {
                    $row = $td->text();
                    array_push($this->results, $row);
                });
            });
        });

        //$remove = array_slice($this->results, 4);
        $obj = [];
        for ($i = 0; $i + 4 < count($this->results); $i += 4) {
            if ($i > 3) {
                $obj[] = [
                    $this->results[0] => $this->results[$i],
                    $this->results[1] => $this->results[$i + 1],
                    $this->results[2] => $this->results[$i + 2],
                    $this->results[3] => $this->results[$i + 3],
                ];
            }
        }

//        dump($obj);
//        print($obj[0]['Code']);
        foreach ($obj as $key => $value) {
            $flag = true;
            if ($value['Code'] == strtoupper($request->code)) {
                $flag = false;
                $validation=Validator::make($request->all(),[
                   $request->code=>'required'
                ]);
                if($validation->failed()){
                    return $this->errorResponse($validation->messages(),404);
                }

                  Bot::create([
                      'user_id'=>auth()->user()->id,
                      'Code'=>$obj[$key]['Code'],
                      'Currency'=>$obj[$key]['Currency'],
                      'Sell'=>$obj[$key]['Sell'],
                      'Buy'=>$obj[$key]['Buy'],
                  ]);

                return  response()->json($obj[$key]);
            }
        }
        if ($flag == true) {
            return $this->errorResponse('Value not found', 404);
        }

    }

}
