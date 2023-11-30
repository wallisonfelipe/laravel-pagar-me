<?php

use Felipe\LaravelPagarMe\Controllers\PagarmeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/pagarme', [PagarmeWebhookController::class, 'index']);
Route::get('/webhook/pagarme', [PagarmeWebhookController::class, 'index']);