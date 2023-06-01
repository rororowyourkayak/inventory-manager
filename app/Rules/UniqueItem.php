<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\Item;

class UniqueItem implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if(Item::where('upc',$value)->where('user_id', auth()->id())->exists()){
            $fail("The given UPC exists in user inventory.");
        }
    }
}
