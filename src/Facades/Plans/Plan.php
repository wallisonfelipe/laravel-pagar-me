<?php

namespace Felipe\LaravelPagarMe\Facades\Plans;

use Felipe\LaravelPagarMe\Facades\Base;

class Plan extends Base
{
    public array $data = [];

    public function withItem(
        string $name,
        int $quantity,
        int $priceInCents,
    ): self
    {
        $this->data["items"][] = [
            "name" => $name,
            "quantity" => $quantity,
            "pricing_scheme" => [
                "price" => $priceInCents
            ]
        ];

        return $this;
    }

    public function create(
        string $name,
        string $description,
        string $statement_descriptor = "Cobrança", #Texto que vai aparecer na fatura
        int $amountInCents,
        array $paymentMethods = ["credit_card", "boleto"],
        int $trialDays = 0
    )
    {
 
        $result = $this->client->post("/core/v5/plans", [
            "json" => array_merge(
                [
                    "name" => $name,
                    "description" => $description,
                    "shippable" => false,
                    "payment_methods" => $paymentMethods,
                    "installments" => [1],
                    "statement_descriptor" => $statement_descriptor,
                    "currency" => "BRL",
                    "interval" => "month",
                    "interval_count" => 1, //Número de intervalos de acordo com a propriedade interval entre cada cobrança da assinatura.
                    "amount" => $amountInCents,
                ],
                $this->data
            )
        ])->getBody()->getContents();

        return $result;
    }

    public function get(string $planId)
    {
        $result = $this->client->get("/core/v5/plans/$planId")->getBody()->getContents();

        return json_decode($result, true);
    }

}
