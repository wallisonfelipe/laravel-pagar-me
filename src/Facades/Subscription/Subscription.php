<?php

namespace Felipe\LaravelPagarMe\Facades\Subscription;

use DateTime;
use Felipe\LaravelPagarMe\Facades\Base;

class Subscription extends Base
{
    public array $billetData = [];
    public array $cardData = [];
    
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

    public function withCard(
        string $holderName,
        string $holderDocument,
        string $number,
        int $expirationYear,
        int $expirationMonth,
        string $cvv
    ) {
        $this->cardData["payment_method"] = "credit_card";
        $this->cardData["card"] = [
            "holder_name" => $holderName,
            "holder_document" => $holderDocument,
            "number" => $number,
            "exp_year" => $expirationYear,
            "exp_month" => $expirationMonth,
            "cvv" => $cvv,
        ];
    }

    public function create(
        string $clientId,
        string $planId,
        ?string $date = null
    ) {
        if (!$clientId){
            throw new \Exception("Client not created!");
        }

        if (empty($this->billetData) && empty($this->cardData)) {
            throw new \Exception("Billing form not decided!");
        }

        $data = array_merge(
            [
                "plan_id" => $planId,
                "customer_id" => $clientId,
                "start_at" => $date
            ],
            $date ? ['start_at' => $date] : [],
            $this->billetData,
            $this->cardData
        );
            
        $result = $this->client->post("/core/v5/subscriptions", [
            "json" => $data
        ])->getBody()->getContents();

        return json_decode($result, true);
    }

}
