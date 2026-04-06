<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InternationalPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;

        // Loose international validation (E.164-ish): optional leading +, digits, spaces and common separators.
        if (!preg_match('/^\+?[0-9][0-9\s().-]*$/', $value)) {
            $fail(__('Invalid phone number format.'));

            return;
        }

        $digits = preg_replace('/\D+/', '', $value);
        $length = is_string($digits) ? strlen($digits) : 0;

        if ($length < 7 || $length > 15) {
            $fail(__('Phone number must contain between 7 and 15 digits.'));
        }
    }
}