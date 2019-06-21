<?php

namespace App\Http\Helpers;

use GuzzleHttp\Client;

class OxfordApi
{
  /**
   * guzzle client
   *
   * @var \GuzzleHttp\Client
   */
  private $guzzle;
  private $strict_match = '?strictMatch=false';
  public function __construct()
  {
    $this->guzzle = new Client();
  }
  /**
   * Call the Api
   *
   * @param $endpoint string
   * @param $method string
   *
   * @return string
   */
  protected function call($endpoint, $method = "GET")
  {
    try {
      // Oxford api requires some headers
      $response = $this->guzzle->request($method, $endpoint, ['headers' => ['app_id' => env("OXFORD_APP_ID"), 'app_key' =>env("OXFORD_APP_KEY")]]);
      if ($response->getStatusCode() == 200) {
        // get the contents of the response
//        $body = $response->getBody()->getContents();
        return $response->getBody()->getContents();
      }
      // if we made it here we need to alert the admin. Something went wrong
      // return the error
      return null;
    } catch(\GuzzleHttp\Exception\TransferException $e) {
      // send email out something went wrong
      // return the error
      return null;
    }
  }

  protected function lexiStats($endpoint, $method = "GET"){

    try {
      // Oxford api requires some headers
      $response = $this->guzzle->request($method, $endpoint, ['headers' => ['app_id' => env("OXFORD_APP_ID"), 'app_key' =>env("OXFORD_APP_KEY")]]);
      if ($response->getStatusCode() == 200) {
        // get the contents of the response
        return $response->getBody()->getContents();
      }
      // if we made it here we need to alert the admin. Something went wrong
      // return the error
      return null;
    } catch(\GuzzleHttp\Exception\TransferException $e) {
      // send email out something went wrong
      // return the error
      return null;
    }


  }

  /*
   * CallApi function
   *
   * @param $word string
   *
   * @return Object
   */
  public function callApi($word)
  {
    $endpoint = env("OXFORD_URL") . "{$word}{$this->strict_match}";
    return $this->call($endpoint, "GET");
//    return array_merge($this->call($endpoint, "GET"),$this->callLexiStats($word));
  }

  public function callLexiStats($word){
    $endpoint = env("OXFORD_API_BASE_URI") . "stats/frequency/words/en/?corpus=nmc&wordform={$word}&collate=lexicalCategory";
    return $this->lexiStats($endpoint, "GET");
  }


}