<?php

namespace Felipe\LaravelPagarMe;

use Felipe\LaravelPagarMe\Entities\Client;
use Felipe\LaravelPagarMe\Facades\Card\Card;
use Felipe\LaravelPagarMe\Facades\Charge\Charges;
use Felipe\LaravelPagarMe\Facades\ClientInterface;
use Felipe\LaravelPagarMe\Facades\Order;
use Felipe\LaravelPagarMe\Facades\Order\CheckoutOrder;
use Felipe\LaravelPagarMe\Facades\Order\CreditCardOrder;
use Felipe\LaravelPagarMe\Facades\Order\PixOrder;
use Felipe\LaravelPagarMe\Facades\Plans\Plan;
use Felipe\LaravelPagarMe\Facades\Subscription\Subscription;

class PagarmeClient {
    public string $apiKey;

    public function __construct(?string $apiKey = null, private ?ClientInterface $client = null ) {
        $this->apiKey = $apiKey ? $apiKey : getenv("PAGARME_SECRET_KEY");
        if (!$this->apiKey) {
            throw new \Exception("Invalid APIKEY");
        }
    }

    public function pixOrder()
    {
        return new PixOrder($this->apiKey, $this->client);
    }

    public function creditCardOrder()
    {
        return new CreditCardOrder($this->apiKey, $this->client);
    }

    public function checkout(): CheckoutOrder
    {
        return new CheckoutOrder($this->apiKey, $this->client);
    }

    public function plan(): Plan
    {
        return new Plan($this->apiKey, $this->client);
    }

    public function subscription()
    {
        return new Subscription($this->apiKey, $this->client);
    }

    public function card()
    {
        return new Card($this->apiKey, $this->client);
    }

    public function client(?string $email = null)
    {
        return new Client($this->apiKey, $email, $this->client);
    }

    public function charge()
    {
        return new Charges($this->apiKey, $this->client);
    }

}