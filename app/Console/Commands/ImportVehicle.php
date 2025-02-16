<?php

namespace App\Console\Commands;

use App\Services\ImportVehicleService;
use Exception;
use Illuminate\Console\Command;

class ImportVehicle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-vehicle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import vehicle data from external source';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(ImportVehicleService $service): void
    {
        $service->process();
    }
}
