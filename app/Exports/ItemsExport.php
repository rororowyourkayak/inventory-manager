<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ItemsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::where('user_id',auth()->id())->orderBy('upc','asc')->select('upc', 'category', 'description', 'quantity')->get();
    }

    public function headings() :array {
        return ['UPC', 'Category', 'Description', 'Quantity'];
    }
}
