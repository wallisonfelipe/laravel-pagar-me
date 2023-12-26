<?php

namespace Felipe\LaravelPagarMe\Facades\Charge;

use Felipe\LaravelPagarMe\Facades\Base;

class Charges extends Base
{
    public function getCharge(
        string $chargeId
    ) {
        $result = $this->client->get("/core/v5/charges/$chargeId")->getBody()->getContents();
        return json_decode($result, true);
    }
}
