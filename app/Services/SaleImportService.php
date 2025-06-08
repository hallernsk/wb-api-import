<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SaleImportService
{
    protected string $baseUrl = 'http://109.73.206.144:6969/api/sales';
    protected string $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function import(string $dateFrom, string $dateTo): int
    {
        Sale::truncate();
        $page = 1;
        $limit = 500;
        $importedCount = 0;

        try {
            while (true) {
                $response = Http::get($this->baseUrl, [
                    'key' => $this->apiKey,
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'page' => $page,
                    'limit' => $limit,
                ]);

                if ($response->failed()) {
                    Log::error('Ошибка при получении продаж', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'page' => $page,
                    ]);
                    break;
                }

                $data = $response->json('data');

                foreach ($data as $item) {
                    Sale::create([      
                        'g_number' => $item['g_number'],
                        'date' => $item['date'],
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'barcode' => $item['barcode'],
                        'total_price' => $item['total_price'],
                        'discount_percent' => $item['discount_percent'],
                        'is_supply' => $item['is_supply'],
                        'is_realization' => $item['is_realization'],
                        'promo_code_discount' => $item['promo_code_discount'],
                        'warehouse_name' => $item['warehouse_name'],
                        'country_name' => $item['country_name'],
                        'oblast_okrug_name' => $item['oblast_okrug_name'],
                        'region_name' => $item['region_name'],
                        'income_id' => $item['income_id'],
                        'sale_id' => $item['sale_id'],
                        'odid' => $item['odid'],
                        'spp' => $item['spp'],
                        'for_pay' => $item['for_pay'],
                        'finished_price' => $item['finished_price'],
                        'price_with_disc' => $item['price_with_disc'],
                        'nm_id' => $item['nm_id'],
                        'subject' => $item['subject'],
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'is_storno' => $item['is_storno'],
                    ]);
                    $importedCount++;
                }

                if (count($data) < $limit) {
                    break;
                }

                $page++;
            }

        } catch (Exception $e) {
            Log::error('Ошибка при импорте продаж: ' . $e->getMessage(), [
                'exception' => $e,
                'page' => $page,
            ]);
        }

        return $importedCount;
    }
}
