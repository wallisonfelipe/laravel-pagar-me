<?php

namespace Felipe\LaravelPagarMe\Dtos;


class QrcodeResponseDto
{
    public function __construct(
        public string $id,
        public string $code,
        public string $amount,
        public string $status,
        public string $created_at,
        public string $updated_at,
        public string $qr_code,
        public string $qr_code_url,
        public string $qr_code_expires_at,
        public string $qr_code_status,
    ) {}
}
