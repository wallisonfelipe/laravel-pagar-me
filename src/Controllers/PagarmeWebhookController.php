<?php

namespace Felipe\LaravelPagarMe\Controllers;

use Felipe\LaravelPagarMe\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class PagarmeWebhookController extends Controller
{
    public function index(Request $request)
    {
        if (!isset($request["data"]["code"])) {
            echo "OK";

            return;
        }

        PaymentLog::create([
            "payment_id" => $request["data"]["code"],
            "payment_processor" => "pagarme",
            "params" => json_encode($request)
        ]);

        echo "OK";
        return;
    }
}
