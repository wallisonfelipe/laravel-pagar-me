<?php

namespace Felipe\LaravelPagarMe\Dtos;


class CreditCardResponseDto
{
    public function __construct(
        public string $id,
        public string $code,
        public string $amount,
        public string $status,
        public string $created_at,
        public string $updated_at,
        public string $gateway_status_code,
        public array $gateway_errors,
    ) {}
}
