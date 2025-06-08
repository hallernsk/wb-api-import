<?php

namespace App\Console\Commands;

use App\Services\OrderImportService;
use Illuminate\Console\Command;

class ImportOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:orders {dateFrom} {dateTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт заказов с внешнего API в базу данных';

    protected OrderImportService $orderImportService;

    public function __construct(OrderImportService $orderImportService)
    {
        parent::__construct();
        $this->orderImportService = $orderImportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');

        try {
            $this->info("Запускаем импорт заказов с {$dateFrom} по {$dateTo}...");
            $count = $this->orderImportService->import($dateFrom, $dateTo);
            $this->info("Импорт успешно завершён. Всего импортировано заказов: {$count}");
        } catch (\Exception $e) {
            $this->error('Ошибка при импорте заказов: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
