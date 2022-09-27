<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class InvoicesExport extends Model
{
  use Exportable;

    public function collection()
    {
        return Invoice::all();
    }
}
