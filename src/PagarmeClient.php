<?php

namespace Felipe\LaravelPagarMe;

use Felipe\LaravelPagarMe\Facades\Order;
use Felipe\LaravelPagarMe\Facades\Order\CheckoutOrder;
use Felipe\LaravelPagarMe\Facades\Order\PixOrder;
use Felipe\LaravelPagarMe\Facades\Plans\Plan;

class PagarmeClient {
    public string $apiKey;

    public function __construct(?string $apiKey = null ) {
        $this->apiKey = $apiKey ? $apiKey : getenv("PAGARME_SECRET_KEY");
        if (!$this->apiKey) {
            throw new \Exception("Invalid APIKEY");
        }
    }

    public function pixOrder()
    {
        return new PixOrder($this->apiKey);
    }

    public function checkout(): CheckoutOrder
    {
        return new CheckoutOrder($this->apiKey);
    }

    public function plan(): Plan
    {
        return new Plan($this->apiKey);
    }

}