<?php

namespace App\Imports;

use App\Models\Item;
use App\Rules\existingCategory;
use App\Rules\UniqueItem;
use App\Rules\UPC;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class TotalItemsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
   

    use Importable;
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
            'user_id' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        //validation rules listed twice because of batch insert validation 
        //according to the Laravel Excel docs, both forms are needed to validate correctly
        return [
           /*  'upc' => ['required', 'regex:/\d{12}/', 'size:12', new UPC, new UniqueItem],
            'category' => ['required', 'max:127', new existingCategory],
            'description' => ['max:511', 'nullable'],
            'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
 */
            '*.upc' => ['required', new UPC, new UniqueItem],
            '*.category' => ['required', 'max:127', new existingCategory],
            '*.description' => ['max:511', 'nullable'],
            '*.quantity' => ['required', 'numeric', 'integer', 'gte:1'],
        ];

    }

    //batch insert is necessary to ensure that all values are screened at the same time
    //batch size is somewhat arbitrary at the moment, but does impose limitation onto the size of a given import
    public function batchSize(): int
    {
        return 1000;
    }

}
