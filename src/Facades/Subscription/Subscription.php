<?php

namespace Felipe\LaravelPagarMe\Facades\Subscription;

use Felipe\LaravelPagarMe\Facades\Base;

class Subscription extends Base
{
    public array $billetData = [];
    
    public function listAll(
        int $page = 1,
        int $size = 10
    )
    {
        $result = $this->client->get("/core/v5/subscriptions?page=$page&size=$size");
        
        return json_decode($result->getBody()->getContents(), true);
    }

    public function withBillet(
        string $dueDays
    )
    {
        $this->billetData["payment_method"] = "boleto";
        $this->billetData["boleto_due_days"] = $dueDays;
        return $this;
    }

    public function create(
        string $clientId,
        string $planId,
        string $paymentMethod = "credit_card",
        string $holderName,
        string $holderDocument,
        string $number,
        int $expirationYear,
        int $expirationMonth,
        string $cvv,
    ) {
        if (!$clientId){
            throw new \Exception("Client not created!");
        }

        $data = array_merge(
            [
                "plan_id" => $planId,
                "payment_method" => $paymentMethod,
                "customer_id" => $clientId,
                "card" => [
                    "holder_name" => $holderName,
                    "holder_document" => $holderDocument,
                    "number" => $number,
                    "exp_year" => $expirationYear,
                    "exp_month" => $expirationMonth,
                    "cvv" => $cvv,
                ]
            ],
            $this->billetData
        );
            
        $result = $this->client->post("/core/v5/subscriptions", [
            "json" => $data
        ])->getBody()->getContents();

        return json_decode($result, true);
    }

}
