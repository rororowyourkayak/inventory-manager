<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use App\Rules\UPC;
use App\Rules\UniqueItem;



class ItemsImport implements ToModel,WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures; 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
            'upc' => $row['upc'],
            'category' => $row['category'],
            'description' => $row['description'],
            'quantity' => $row['quantity'],
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => auth()->id()
        ]);
    }


    public function rules(): array {
        return[
            'upc' => ['required', 'regex:/\d{12}/', 'size:12', new UPC, new UniqueItem],
        'category' => ['required', 'max:127'],
        'description' => ['max:511', 'nullable'],
        'quantity' => ['required', 'numeric', 'integer', 'gte:1'],

        '*.upc' => ['required', 'regex:/\d{12}/', 'size:12', new UPC, new UniqueItem],
        '*.category' => ['required', 'max:127'],
        '*.description' => ['max:511','nullable'],
        '*.quantity' => ['required', 'numeric', 'integer', 'gte:1'],
        ];
        
    }


}
