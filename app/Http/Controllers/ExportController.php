<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportAsXLSX(){
        return Excel::download(new ItemsExport, 'inventory.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportAsCSV(){
        return Excel::download(new ItemsExport, 'inventory.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
       
    }
}
