<?php

namespace Felipe\LaravelPagarMe\Facades;

interface ClientInterface
{
    public function get(string $url, ?array $params): ClientInterface;
    public function post(string $url, ?array $params): ClientInterface;
    public function getBody(): ClientInterface|string;
    public function getContents(): string;
}