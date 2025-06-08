<?php

namespace App\Console\Commands;

use App\Services\StockImportService;
use Illuminate\Console\Command;

class ImportStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:stocks {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт складов за указанную дату';

    protected StockImportService $stockImportService;

    public function __construct(StockImportService $stockImportService)
    {
        parent::__construct();
        $this->stockImportService = $stockImportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date');

        try {
            $this->info("Запускаем импорт складов за {$date}...");
            $count = $this->stockImportService->import($date);
            $this->info("Импорт успешно завершён. Всего импортировано складов: {$count}");
        } catch (\Exception $e) {
            $this->error('Ошибка при импорте складов: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
