<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\CustomFunctions; 

class UPC implements InvokableRule
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
        if(!CustomFunctions::isValidUPC($value)){
            $fail('The given UPC is not a valid UPC.');
        }
    }
}
