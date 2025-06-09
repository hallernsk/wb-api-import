<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class BaseApiService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.wb.base_url');
        $this->apiKey = config('services.wb.api_key');
    }

    protected function makeRequest(string $endpoint, array $params = []): Response
    {
        $params['key'] = $this->apiKey;
        return Http::get("{$this->baseUrl}{$endpoint}", $params);
    }
}
