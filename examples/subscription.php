<?php

require __DIR__ . "/../vendor/autoload.php";

use Felipe\LaravelPagarMe\PagarmeClient;

$SECRET_KEY = "YOUR_KEY_HERE";

$instance = new PagarmeClient($SECRET_KEY);


$client = $instance->client();
$client->create(
    "Wallison Felipe",
    "wallisonfelipe99@hotmail.com",
    "06600363126",
    "CPF",
    "male",
    "1999-09-20", //TODO: Ajustar a data
    "67981071372",
    "67981071372",
);

$plan = $instance->plan()->withItem("Item 1", 1, 1000)->create(
    "Primeiro plano teste",
    "Plano de teste",
    "A",
    1000
);

$subscription = $instance->subscription()->create(
    $client,
    $plan["id"],
    "credit_card",
    "APRO",
    "06600363126",
    "4000000000000010",
    2029,
    12,
    "123"
);



header("Content-Type: application/json");

echo json_encode($subscription, JSON_PRETTY_PRINT);