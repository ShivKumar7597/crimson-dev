<?php

namespace App\Models;


use Exception;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class CurrentRmsService{

    protected $client;
    protected $api_token;
    protected $sub_domain;


    public function __construct($api_token, $sub_domain)
    {

        $this->cache_length = config('services.current.cache_length');
        $this->api_token = $api_token;
        $this->sub_domain = $sub_domain;

		$this->client = new Client([
			'cookies' => false,
			'headers' => array(
				"X-AUTH-TOKEN" => $this->api_token,
				"X-SUBDOMAIN" => $this->sub_domain
			),
			'base_uri' => config('services.current.base_uri').config('services.current.version')."/",
			'http_errors' => true
        ]);
    }

    public function get($stub, $params, $array = array())
	{
		return $this->build('get', $stub, $params, $array);
	}

    public function build($method, $stub, $params, $array = array())
	{
		try {
			$path = $stub."?".$this->params($params);

			// do live request
            $data = $this->client->request($method, $path, ['json' => $array])->getBody()->getContents();

			// json_decode the object
            return json_decode($data, true);
            
		} catch (ClientException | RequestException $e) {
             dd($e);
            $response = $e->getResponse();
            throw new \Exception("Something failed: { $response->getStatusCode() } ");
	 	} 
    }
    
    private function params($array)
	{
		$str = "";
		$i = 0;

		foreach($array as $a => $k) {
			$str .= $a."=".$k;

			if(++$i != count($array)) {
				$str .= "&";
			}
		}

		return $str;
	}
}