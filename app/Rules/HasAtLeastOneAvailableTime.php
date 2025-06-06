<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasAtLeastOneAvailableTime implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            $fail('The :attribute must be a valid set of daily availabilities.');
            return;
        }

        $hasAvailability = false;

        foreach ($value as $dayTimes) {
            if (is_array($dayTimes) && count($dayTimes) > 0) {
                $hasAvailability = true;
                break;
            }
        }

        if (!$hasAvailability) {
            $fail('You must provide at least one available time slot for any day.');
        }
    }
}
