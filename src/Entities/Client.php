<?php

namespace Felipe\LaravelPagarMe\Entities;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private string $url = "https://api.pagar.me";
    public GuzzleClient $client;

    public string $id = "";
    private string $name = "";
    private ?string $email = null;
    private array $address = [];

    public function __construct(
        public string $apiKey,
        ?string $email = ""
    )
    {
        $this->client = new GuzzleClient([
            "base_uri" => $this->url,
            "headers" => [
                "Accept" => "application/json",
                "Authorization" => "Basic " . base64_encode($this->apiKey . ":"),
            ]
        ]);

        if ($email) {
            $this->email = $email;
            $this->get($email);
        }
    
    }

    private function fillEntity()
    {
        $result = $this->client->get("/core/v5/customers?page=1&size=10&email=". ($this->email ? $this->email : "null"))->getBody()->getContents();
        $result = $result ? json_decode($result, true) : [];
        $result = isset($result["data"][0]) ? $result["data"][0] : [];

        $this->id = $result["id"] ?? "";
        $this->name = $result["name"] ?? "";
        $this->email = $result["email"] ?? "";
    }

    public function get()
    {
        if (!$this->id) {
            $this->fillEntity();
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
        ];
    }
    
    public function withAddress(
        string $state,
        string $city,
        string $zipcode,
        string $number,
        string $street,
        string $neighborhood,
        ?string $complement = null
    ) {
        $this->address = [
            'country' => 'BR',
            'state' => $state,
            'city' => $city,
            'zip_code' => $zipcode,
            'line_1' => "$number,$street,$neighborhood",
            'line_2' => "$complement",
        ];
        
        return $this;
    }

    public function create(
        string $name,
        string $email,
        string $documentNumber,
        string $documentType,
        string $mobilePhone,
        string $homePhone,
        ?string $gender = null,
        ?string $birthdate = null,
        ?array $metadata = null,
        ?string $code = null,
    )
    {
        $data = array_merge([
                "type" => $documentType == "CNPJ" ? "company" : "individual",
                "name" => $name,
                "email" => $email,
                "document_type" => $documentType,
                "document" => $documentNumber,
                "metadata" => $metadata,
                "code" => $code,
                "phones" => [
                    "home_phone" => [
                        "country_code" => "55",
                        "area_code" => substr($homePhone, 0, 2),
                        "number" => substr($homePhone, 2)
                    ],
                    "mobile_phone" => [
                        "country_code" => "55",
                        "area_code" => substr($mobilePhone, 0, 2),
                        "number" => substr($mobilePhone, 2)
                    ],
                ]
            ],
            $gender ? ["gender" => $gender]: [],
            $birthdate ? ["birthdate" => $birthdate]: [],
            !empty($this->address) ? ['address' => $this->address] : []
        );

        $result = $this->client->post("/core/v5/customers", [
            "json" => $data
        ]);
        
        $result = json_decode($result->getBody()->getContents(), true);

        $this->id = $result["id"];
        $this->name = $result["name"];
        $this->email = $result["email"];

        return $this;
    }

}
