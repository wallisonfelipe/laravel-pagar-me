<?php

require __DIR__ . "/../vendor/autoload.php";

use Felipe\LaravelPagarMe\PagarmeClient;

$SECRET_KEY = "sk_test_812630862df3440d8cd081a3b277493f";

$instance = new PagarmeClient($SECRET_KEY);


$client = $instance->client();
$client->create(
    name: "Wallison Felipe",
    email: "wallisonfelipe99@hotmail.com",
    documentNumber:"08144600037",
    documentType: "CPF",
    mobilePhone: "67981071372",
    homePhone: "67981071372",
    birthdate: "1999-09-20", //TODO: Ajustar a data
);

$creditCardInstance  = $instance->creditCardOrder();

$response = $creditCardInstance->withCard(
    $client->get()["name"],
    "08144600037",
    "4000000000000010",
    2029,
    12,
    "123",
    "Cobranca de pagamento" //Não pode conter acentos
)->create(
    $client,
    1000,
    "Cobranca de pagamento" //Não pode conter acentos
);

dd($response);