<?php

namespace App\Rules;

use App\Models\SchoolYear as SY;
use Illuminate\Contracts\Validation\InvokableRule;

class SchoolYear implements InvokableRule
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
        $school_year = $value . '-' . ($value + 1);
        $record = SY::where('name', $school_year)->first();
        if ($record) {
            $fail("The school year $school_year already exists.");
        }
    }
}
