<?php

use Felipe\LaravelPagarMe\Facades\Order;

class PagarmeClient {
    private string $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function order()
    {
        return new Order($this->apiKey);
    }

}