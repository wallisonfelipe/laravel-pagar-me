<?php

namespace Felipe\LaravelPagarMe\Facades\Order;

use Felipe\LaravelPagarMe\Dtos\CreditCardResponseDto;
use Felipe\LaravelPagarMe\Dtos\QrcodeResponseDto;
use Felipe\LaravelPagarMe\Entities\Client;
use Felipe\LaravelPagarMe\Facades\Base;

class CreditCardOrder extends Base
{
    public array $cardData = [];


    public function listAll()
    {
        $result = $this->client->get("/core/v5/orders");
        
        return $result->getBody()->getContents();
    }

    public function withCard(
        string $holderName,
        string $holderDocument,
        string $number,
        int $expirationYear,
        int $expirationMonth,
        string $cvv,
        string $description
    ) {
        $this->cardData = [
            "holder_name" => $holderName,
            "holder_document" => $holderDocument,
            "number" => $number,
            "exp_year" => $expirationYear,
            "exp_month" => $expirationMonth,
            "cvv" => $cvv,
            'statement_descriptor' => $description
        ];

        return $this;
    }

    public function create(
        Client $client,
        int $amountInCents,
        string $description,
        ?string $code = ""
    ): CreditCardResponseDto
    {
        if (!isset($client->get()["id"]) || !$client->get()["id"]) {
            throw new \Exception("Cliente não encontrado");
        }

        $data = [
            "customer_id" => $client->get()["id"],
            "items" => [[
                "amount"      => $amountInCents,
                "description" => substr($description, 0, 13),
                "quantity"    => 1,
                "code"        => $code ?? ""
            ]],
            "payments" => [[
                "payment_method" => "credit_card",
                "credit_card" => [
                    "recurrence" => false,
                    "installments" => 1,
                    "statement_descriptor" => substr($description, 0, 13),
                    "card" => $this->cardData
                ]
            ]]
        ];

        $result = $this->client->post("/core/v5/orders", [
            "json" => $data
        ])->getBody()->getContents();
        
        $result = json_decode($result, true);

        $this->verifyErrors($result);
        
        return $this->format($result);
    }

    public function format(array $response)
    {
        return new CreditCardResponseDto(
            $response["id"],
            $response["code"],
            $response["amount"],
            $response["status"],
            $response["created_at"],
            $response["updated_at"],
            $response["charges"][0]["last_transaction"]["gateway_response"]["code"],
            $response["charges"][0]["last_transaction"]["gateway_response"]["errors"],
        );
    }

}
