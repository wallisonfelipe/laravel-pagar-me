<?php

namespace Felipe\LaravelPagarMe\Facades\Card;

use Felipe\LaravelPagarMe\Entities\Client;
use Felipe\LaravelPagarMe\Facades\Base;

class Card extends Base
{
    public function create(
        Client $client,
        string $cardToken,
    )
    {
        if (!$client->id){
            throw new \Exception("Client not created!");
        }
   
      
        $result = $this->client->post("/core/v5/customers/{$client->id}/cards", [
            "json" => [
                "private_label" => false,
                "options" => [
                    "verify" => true
                ],
                "token" => $cardToken
            ]
        ]);
  
        return json_decode($result->getBody()->getContents(), true);
    }

    public function generateToken(
        string $publicKey,
        string $holderName,
        string $number,
        string $expirationYear,
        string $expirationMonth,
        string $cvv,
    )
    {
   
      
        $result = $this->client->post("/core/v5/tokens?appId={$publicKey}", [
            "json" => [
                "type" => "card",
                "card" => [
                    "number" => $number,
                    "holder_name" => $holderName,
                    "exp_month" => $expirationMonth,
                    "exp_year" => $expirationYear,
                    "cvv" => $cvv,
                ],
               
            ]
        ]);
  
        return json_decode($result->getBody()->getContents(), true);
    }

}
