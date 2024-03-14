<?php

namespace Felipe\LaravelPagarMe\Facades\Order;

use Felipe\LaravelPagarMe\Dtos\QrcodeResponseDto;
use Felipe\LaravelPagarMe\Entities\Client;
use Felipe\LaravelPagarMe\Facades\Base;

class PixOrder extends Base
{
    public function listAll()
    {
        $result = $this->client->get("/core/v5/orders");
        
        return $result->getBody()->getContents();
    }

    public function create(
        Client $client,
        int $amountInCents,
        string $description,
        ?int $expireTimeInSeconds = 7200,
        ?string $code = ""
    ): QrcodeResponseDto
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
                "payment_method" => "pix",
                "pix" => [
                    "expires_in" => $expireTimeInSeconds
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
        return new QrcodeResponseDto(
            $response["id"],
            $response["code"],
            $response["amount"],
            $response["status"],
            $response["created_at"],
            $response["updated_at"],
            $response["charges"][0]["last_transaction"]["qr_code"],
            $response["charges"][0]["last_transaction"]["qr_code_url"],
            $response["charges"][0]["last_transaction"]["expires_at"],
            $response["charges"][0]["last_transaction"]["status"],
        );
    }

}
