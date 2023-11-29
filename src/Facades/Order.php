<?php

namespace Felipe\LaravelPagarMe\Facades;

use GuzzleHttp\Client;

class Order extends Base
{
    public function listAll()
    {
        $result = $this->client->get("/core/v5/orders");
        
        return $result->getBody()->getContents();
    }

    public function create()
    {
        echo "Criando pagamento";
    }
}
