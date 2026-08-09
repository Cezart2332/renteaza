<?php

namespace App\Console\Commands;

use App\Imports\VehiclesImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Excel;

class ImportVehicles extends Command
{
    protected $signature = 'import:vehicles';
    protected $description = 'Import vehicles from Excel file';

  public function handle(Excel $excel)
{
  $excel->import(new VehiclesImport, public_path('excels/cars_new.xlsx'));

    $this->info('Import finalizat cu succes!');
}
}
