<?php

namespace Felipe\LaravelPagarMe\Entities;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private string $url = "https://api.pagar.me";
    public GuzzleClient $client;

    private string $id = "";
    private string $name;
    private string $email;
    private string $documentNumber;
    private string $documentType;
    private string $gender;
    private string $birthdate;
    private string $mobilePhone;
    private string $homePhone;
    private ?array $metadata = null;
    private ?string $code = null;

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

    public function getId()
    {
        return $this->id;
    }

    private function get()
    {
        $result = $this->client->get("/core/v5/customers?page=1&size=10&email=$this->email")->getBody()->getContents();
        $result = $result ? json_decode($result, true) : [];

        if (!isset($result["data"][0])) {
            return [];
        }

        $result = $result["data"][0];

        $this->id = $result["id"];

        return $result;
    }
    
    public function create(
        string $name,
        string $email,
        string $documentNumber,
        string $documentType,
        string $gender,
        string $birthdate,
        string $mobilePhone,
        string $homePhone,
        ?array $metadata = null,
        ?string $code = null,
    )
    {
        $data = [
            "type" => $documentType == "CNPJ" ? "company" : "individual",
            "name" => $name,
            "email" => $email,
            "document_type" => $documentType,
            "document" => $documentNumber,
            "gender" => $gender,
            "birthdate" => $birthdate,
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
        ];

        $result = $this->client->post("/core/v5/customers", [
            "json" => $data
        ]);
        
        $result = json_decode($result->getBody()->getContents(), true);

        $this->id = $result["id"];

        return $this;
    }

}
