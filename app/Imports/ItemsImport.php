<?php

namespace App\Imports;

use App\Models\Item;
use App\Rules\existingCategory;
use App\Rules\UniqueItem;
use App\Rules\UPC;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    public $incrementedUPCS = [];
    public $errors = [];

    use Importable/* SkipsFailures */;
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
            'upc' => ['required', new UPC, new UniqueItem],
            'category' => ['required', 'max:127', new existingCategory],
            'description' => ['max:511', 'nullable'],
            'quantity' => ['required', 'numeric', 'integer', 'gte:1'],

            '*.upc' => ['required', new UPC, new UniqueItem],
            '*.category' => ['required', 'max:127', new existingCategory],
            '*.description' => ['max:511', 'nullable'],
            '*.quantity' => ['required', 'numeric', 'integer', 'gte:1'],
        ];

    }

    public function onFailure(Failure...$failures)
    {
        
        foreach ($failures as $failure) {

            /* if the failure is because of existing UPC,  increment the item by the quantity in the csv*/
            if ($failure->attribute() == 'upc' && $failure->errors()[0] == "The given UPC exists in user inventory.") {
                $current_quantity = Item::where('upc', $failure->values()['upc'])->where('user_id', auth()->id())->first()["quantity"];
                $additional_quantity = number_format($failure->values()['quantity']);

                $current_quantity += $additional_quantity;

                try {
                    Item::where('upc', $failure->values()['upc'])->where('user_id', auth()->id())->update(['quantity' => $current_quantity]);
                } catch (\Illuminate\Database\QueryException $e) {}

                //add the upc to array so that it can be displayed later
                $this->incrementedUPCS[$failure->values()['upc']] = $current_quantity;
            } else {
                //if it is not a duplicate error, hand it to the error list
                array_push($this->errors, $failure);
            }
        }
    }

}
