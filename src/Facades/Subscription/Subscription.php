<?php

namespace Felipe\LaravelPagarMe\Facades\Subscription;

use Carbon\Carbon;
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
        $this->billetData["capture"] = true;
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
        ?Carbon $date = null
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
            ],
            $date ? ['start_at' => $date->startOfDay()->format('Y-m-d\TH:i:s\Z')] : [],
            $this->billetData,
            $this->cardData
        );
            
        $result = $this->client->post("/core/v5/subscriptions", [
            "json" => $data
        ])->getBody()->getContents();

        return json_decode($result, true);
    }

    public function changeBillingDate(
        string $subscriptionId,
        Carbon $newDate #"2022-01-25"
    ) {
        $result = $this->client->patch("/core/v5/subscriptions/$subscriptionId/billing-date", [
            "next_billing_at" => $newDate->format('Y-m-d')
        ])->getBody()->getContents();

        return json_decode($result, true);
    }

    public function cancelSubscription(
        string $subscriptionId
    ) {
        $result = $this->client->delete("/core/v5/subscriptions/$subscriptionId")->getBody()->getContents();

        return json_decode($result, true);
    }

    public function getSubscription(
        string $subscriptionId
    ) {
        $result = $this->client->get("/core/v5/subscriptions/$subscriptionId")->getBody()->getContents();
        return json_decode($result, true);
    }

    public function getCicles(
        string $subscriptionId
    ) {
        $result = $this->client->get("/core/v5/subscriptions/$subscriptionId/cycles")->getBody()->getContents();
        return json_decode($result, true);
    }

    public function getCicleData(
        string $subscriptionId,
        string $cicleId
    ) {
        $result = $this->client->get("/core/v5/subscriptions/$subscriptionId/cycles/$cicleId")->getBody()->getContents();
        return json_decode($result, true);
    }
    
    public function getInvoices(
        string $subscriptionId
    ) {
        $result = $this->client->get("/core/v5/invoices?subscription_id=$subscriptionId&page=1&size=10")->getBody()->getContents();
        return json_decode($result, true);
    }
}
