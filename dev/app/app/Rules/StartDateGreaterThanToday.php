<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StartDateGreaterThanToday implements Rule
{
    public function passes($attribute, $value)
    {
        return strtotime($value) >= strtotime(today());
    }

    public function message()
    {
        return __('The :attribute must be a date greater than today.');
    }
}