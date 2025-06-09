<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderImportService extends BaseApiService
{
    public function import(string $dateFrom, string $dateTo): int
    {
        Order::truncate();

        $page = 1;
        $limit = 500;
        $importedCount = 0;

        try {
            while (true) {
                $response = $this->makeRequest('/orders', [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'page' => $page,
                    'limit' => $limit,
                ]);

                if ($response->failed()) {
                    Log::error('Ошибка при получении заказов', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'page' => $page,
                    ]);
                    break;
                }

                $data = $response->json('data');

                foreach ($data as $item) {
                    Order::create([
                        'g_number' => $item['g_number'],
                        'date' => $item['date'],
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'barcode' => $item['barcode'],
                        'total_price' => $item['total_price'],
                        'discount_percent' => $item['discount_percent'],
                        'warehouse_name' => $item['warehouse_name'],
                        'oblast' => $item['oblast'],
                        'income_id' => $item['income_id'],
                        'odid' => $item['odid'],
                        'nm_id' => $item['nm_id'],
                        'subject' => $item['subject'],
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'is_cancel' => $item['is_cancel'],
                        'cancel_dt' => $item['cancel_dt'],
                    ]);
                    $importedCount++;
                }

                if (count($data) < $limit) break;

                $page++;
            }
        } catch (Exception $e) {
            Log::error('Ошибка при импорте заказов: ' . $e->getMessage(), [
                'exception' => $e,
                'page' => $page,
            ]);
        }

        return $importedCount;
    }
}
