<?php

namespace App\Http\Requests\Personnel;

use App\Enums\CountryCode;
use App\Rules\InternationalPhoneNumber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonnelRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'personal_code' => ['required', 'string', 'max:255', 'unique:personnel,personal_code'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'birthday_date' => ['nullable', 'date', 'before_or_equal:today'],
            'phone_number' => ['required', 'string', 'max:32', new InternationalPhoneNumber],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:personnel,email'],

            'country_code' => ['nullable', 'string', 'size:2', Rule::in(CountryCode::values())],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'street_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:10000'],

            'portrait_photo' => ['nullable', 'image', 'max:5120'],
            'remove_portrait_photo' => ['sometimes', 'boolean'],

            'workplaces' => ['nullable', 'array'],
            'workplaces.*.id' => ['nullable', 'integer'],
            'workplaces.*.company_id' => ['required', 'integer', 'exists:companies,id'],
            'workplaces.*.occupation_id' => ['required', 'integer', 'exists:occupations,id'],
            'workplaces.*.from_date' => ['nullable', 'date'],
            'workplaces.*.to_date' => ['nullable', 'date'],
        ];
    }
}