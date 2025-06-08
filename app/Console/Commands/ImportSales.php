<?php

namespace App\Console\Commands;

use App\Services\SaleImportService;
use Illuminate\Console\Command;

class ImportSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sales {dateFrom} {dateTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт продаж с внешнего API в базу данных';

    protected SaleImportService $saleImportService;

    public function __construct(SaleImportService $saleImportService)
    {
        parent::__construct();
        $this->saleImportService = $saleImportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');

        try {
            $this->info("Запускаем импорт продаж с {$dateFrom} по {$dateTo}...");
            $count = $this->saleImportService->import($dateFrom, $dateTo);
            $this->info("Импорт успешно завершён. Всего импортировано продаж: {$count}");
        } catch (\Exception $e) {
            $this->error('Ошибка при импорте продаж: ' . $e->getMessage());
            return 1; 
        }

        return 0; 
    }
}
