<?php

namespace Felipe\LaravelPagarMe\Facades\Order;

use Felipe\LaravelPagarMe\Entities\Client;
use Felipe\LaravelPagarMe\Facades\Base;

class PixOrder extends Base
{
    public function listAll()
    {
        $result = $this->client->get("/core/v5/orders");
        
        return $result->getBody()->getContents();
    }

    private function verifyErrors(array $response)
    {
        if ($response["status"] == "failed") {
            if (isset($response["charges"][0]["last_transaction"]["gateway_response"]["errors"][0]["message"])) {
                throw new \Exception($response["charges"][0]["last_transaction"]["gateway_response"]["errors"][0]["message"]);
            }
            
            throw new \Exception("Erro ao criar pagamento");
        }
    }

    public function create(
        Client $client,
        int $amountInCents,
        string $description,
        ?int $expireTimeInSeconds = 7200,
        ?string $code = ""
    )
    {
        $data = [
            "customer_id" => $client->getId(),
            "items" => [[
                "amount"      => $amountInCents,
                "description" => $description,
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
        return [
            "id"                 => $response["id"],
            "code"               => $response["code"],
            "amount"             => $response["amount"],
            "status"             => $response["status"],
            "created_at"         => $response["created_at"],
            "updated_at"         => $response["updated_at"],
            "qr_code"            => $response["charges"][0]["last_transaction"]["qr_code"],
            "qr_code_url"        => $response["charges"][0]["last_transaction"]["qr_code"],
            "qr_code_expires_at" => $response["charges"][0]["last_transaction"]["expires_at"],
            "qr_code_status"     => $response["charges"][0]["last_transaction"]["status"],
        ];
    }

}
