<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class StockImportService
{
    protected string $baseUrl = 'http://109.73.206.144:6969/api/stocks';
    protected string $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function import(string $date): int
    {
        Stock::truncate();
        $page = 1;
        $limit = 500;
        $importedCount = 0;

        try {
            while (true) {
                $response = Http::get($this->baseUrl, [
                    'key' => $this->apiKey,
                    'dateFrom' => $date,
                    'page' => $page,
                    'limit' => $limit,
                ]);

                if ($response->failed()) {
                    Log::error('Ошибка при получении складов', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'page' => $page,
                    ]);
                    break;
                }

                $data = $response->json('data');

                foreach ($data as $item) {
                    Stock::create([
                        'date' => $item['date'],
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'barcode' => $item['barcode'],
                        'quantity' => $item['quantity'],
                        'is_supply' => $item['is_supply'],
                        'is_realization' => $item['is_realization'],
                        'quantity_full' => $item['quantity_full'],
                        'warehouse_name' => $item['warehouse_name'],
                        'in_way_to_client' => $item['in_way_to_client'],
                        'in_way_from_client' => $item['in_way_from_client'],
                        'nm_id' => $item['nm_id'],
                        'subject' => $item['subject'],
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'sc_code' => $item['sc_code'],
                        'price' => $item['price'],
                        'discount' => $item['discount'],
                    ]);
                    $importedCount++;
                }

                if (count($data) < $limit) {
                    break;
                }

                $page++;
            }

        } catch (Exception $e) {
            Log::error('Ошибка при импорте складов: ' . $e->getMessage(), [
                'exception' => $e,
                'page' => $page,
            ]);
        }

        return $importedCount;
    }
}
