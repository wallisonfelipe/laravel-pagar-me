<?php

namespace Felipe\LaravelPagarMe\Facades;

use GuzzleHttp\Client;

abstract class Base
{
    private string $url = "https://api.pagar.me";
    public Client $client;

    public function __construct(
        public string $apiKey
    )
    {
        $this->client = new Client([
            "base_uri" => $this->url,
            "headers" => [
                "Accept" => "application/json",
                "Authorization" => "Basic " . base64_encode($this->apiKey . ":"),
            ]
        ]);

    }

}
