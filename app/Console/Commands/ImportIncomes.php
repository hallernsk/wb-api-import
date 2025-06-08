<?php

namespace App\Console\Commands;

use App\Services\IncomeImportService;
use Illuminate\Console\Command;

class ImportIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:incomes {dateFrom} {dateTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт доходов с внешнего API в базу данных';

    protected IncomeImportService $incomeImportService;
    public function __construct(IncomeImportService $incomeImportService)
    {
        parent::__construct();
        $this->incomeImportService = $incomeImportService;
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
    $dateFrom = $this->argument('dateFrom');
    $dateTo = $this->argument('dateTo');

    try {
        $this->info("Запускаем импорт доходов с {$dateFrom} по {$dateTo}...");
        $count = $this->incomeImportService->import($dateFrom, $dateTo);
        $this->info("Импорт успешно завершён. Всего импортировано доходов: {$count}");
    } catch (\Exception $e) {
        $this->error('Ошибка при импорте доходов: ' . $e->getMessage());
        return 1;
    }

    return 0;
    }
}
