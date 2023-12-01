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
                "Content-Type" => "application/json",
                "Authorization" => "Basic " . base64_encode($this->apiKey . ":"),
            ]
        ]);

    }

    public function verifyErrors(array $response)
    {
        if ($response["status"] == "failed") {
            if (isset($response["charges"][0]["last_transaction"]["gateway_response"]["errors"][0]["message"])) {
                throw new \Exception($response["charges"][0]["last_transaction"]["gateway_response"]["errors"][0]["message"]);
            }
            
            throw new \Exception("Erro ao criar pagamento");
        }
    }

}
