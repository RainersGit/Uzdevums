<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;


class DataController extends Controller
{
    protected $fileCaching;

    public function __construct(CacheInterface  $fileCaching)
    {
        $this->fileCaching = $fileCaching;
    }


    public function postRequest()
    {
        $client = new \GuzzleHttp\Client();

        $apikey = base64_encode('77qn9aax-qrrm-idki:lnh0-fm2nhmp0yca7');

        $apiurl = 'https://api.printful.com/shipping/rates';

        $seconds = 10;

        $cacheKey = sha1((string) $apiurl); 

        $response='';
        $cacheresultmsg='';

        if ( ! $this->fileCaching->find($cacheKey) ) {
            try {
                $response = $client->post($apiurl,
                ['headers' => [
                    'Authorization' => 'Basic '.$apikey,
                    'Accept' => 'application/json'
                ],
                'json' => [
                    'recipient' => ['address1' => '11025 Westlake Dr','city' => 'Charlotte','country_code' => 'US','state_code' => 'NC','zip' => '28273'],
                    'items' => [['quantity' => 2,'variant_id' => 7679]]
                ]
                ]);

                $response = $response->getBody()->getContents();
                
                $this->fileCaching->set($cacheKey,$response,$seconds);
                
                echo '<p>echo - Made API call - Result cached for '.$seconds.' seconds</p>';

            } catch (TransferException $e) {
                echo '<p>echo - API request failed:</p> ';
                echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo '<p>echo - Exception response:</p> ';
                echo Psr7\str($e->getResponse());
            }
        }
        }else{
            $response = $this->fileCaching->get($cacheKey);
            echo '<p>echo - Made Cache call</p> ';
        }

        return view('result', ['response' => $response]);
    }

}
