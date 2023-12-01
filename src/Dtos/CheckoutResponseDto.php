<?php

namespace Felipe\LaravelPagarMe\Dtos;


class CheckoutResponseDto
{
    public function __construct(
        public string $id,
        public string $code,
        public string $amount,
        public string $status,
        public string $created_at,
        public string $updated_at,
        public string $link
    ) {}
}
