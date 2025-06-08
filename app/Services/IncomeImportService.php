<?php

namespace App\Services;

use App\Models\Income;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class IncomeImportService
{
    protected string $baseUrl = 'http://109.73.206.144:6969/api/incomes';
    protected string $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function import(string $dateFrom, string $dateTo): int
    {
        Income::truncate();

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
                    Log::error('Ошибка при получении доходов', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'page' => $page,
                    ]);
                    break;
                }

                $data = $response->json('data');

                foreach ($data as $item) {
                    Income::create([
                        'income_id' => $item['income_id'],
                        'number' => $item['number'],
                        'date' => $item['date'],
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'barcode' => $item['barcode'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total_price'],
                        'date_close' => $item['date_close'],
                        'warehouse_name' => $item['warehouse_name'],
                        'nm_id' => $item['nm_id'],
                    ]);

                    $importedCount++;
                }

                if (count($data) < $limit) {
                    break;
                }                

                $page++;
            }

        } catch (Exception $e) {
            Log::error('Ошибка при импорте доходов: ' . $e->getMessage(), [
                'exception' => $e,
                'page' => $page,
            ]);
        }

        return $importedCount;
    }
}
