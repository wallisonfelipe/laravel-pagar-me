<?php

namespace Felipe\LaravelPagarMe\Facades\Order;

use Carbon\Carbon;
use Felipe\LaravelPagarMe\Dtos\CheckoutResponseDto;
use Felipe\LaravelPagarMe\Entities\Client;
use Felipe\LaravelPagarMe\Facades\Base;

class CheckoutOrder extends Base
{
    private array $data;

    public function __construct(string $apiKey)
    {
        parent::__construct($apiKey);
        $this->data = [
            "customer_id" => "",
            "items" => [],
            "payments" => [[
                "payment_method" => "checkout",
                "checkout" => [
                    "expires_in" => 0,
                    "default_payment_method" => "",
                ]
            ]]
        ];
    }

    public function withBillet(string $dueDate, string $instructions)
    {
        $this->data["payments"][0]["checkout"]["accepted_payment_methods"][] = "boleto";
        $this->data["payments"][0]["checkout"]["boleto"] = [
            "due_at" => Carbon::parse($dueDate)->toIso8601ZuluString(),
            "instructions" => $instructions
        ];

        return $this;
    }

    public function withPix(
        ?int $expireTimeInSeconds = 7200,
    ) {
        $this->data["payments"][0]["checkout"]["accepted_payment_methods"][] = "pix";
        $this->data["payments"][0]["checkout"]["pix"] = [
            "expires_in" => $expireTimeInSeconds
        ];

        return $this;
    }

    public function withCreditCard(
        string $description
    ) {
        $this->data["payments"][0]["checkout"]["accepted_payment_methods"][] = "credit_card";
        $this->data["payments"][0]["checkout"]["credit_card"] = [
            "statement_descriptor" => $description,
        ];

        return $this;
    }

    public function create(
        Client $client,
        int $amountInCents,
        string $description,
        ?int $expireTimeInSeconds = 7200,
        ?string $code = "",
        ?string $urlCallback =    ""
    ): CheckoutResponseDto {
        if (!isset($client->get()["id"]) || !$client->get()["id"]) {
            throw new \Exception("Client not found");
        }
        if (!isset($this->data["payments"][0]["checkout"]["accepted_payment_methods"][0])) {
            throw new \Exception("To create a checkout order, you must specify at least one payment method");
        }
        $this->data["payments"][0]["success_url"] = $urlCallback;
        $this->data["customer_id"] = $client->get()["id"];
        $this->data["items"] = [[
            "amount"      => $amountInCents,
            "description" => $description,
            "quantity"    => 1,
            "code"        => $code ?? ""
        ]];
        $this->data["payments"][0]["checkout"]["expires_in"] = $expireTimeInSeconds;
        $this->data["payments"][0]["checkout"]["default_payment_method"] = $this->data["payments"][0]["checkout"]["accepted_payment_methods"][0];



        $result = $this->client->post("/core/v5/orders", [
            "json" => $this->data
        ])->getBody()->getContents();


        $result = json_decode($result, true);

        $this->verifyErrors($result);

        return $this->format($result);
    }

    public function format(array $response)
    {

        return new CheckoutResponseDto(
            $response["id"],
            $response["code"],
            $response["amount"],
            $response["status"],
            $response["created_at"],
            $response["updated_at"],
            $response["checkouts"][0]["payment_url"],
            $response["checkouts"][0]["payment_url"],
        );
    }
}
