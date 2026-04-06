<?php

namespace App\Http\Requests\Companies;

use App\Enums\CountryCode;
use App\Models\Company;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var \App\Models\Company|null $company */
        $company = $this->route('company');

        return [
            'name' => ['required', 'string', 'max:255'],
            'registration_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'registration_no')->ignore($company?->id),
            ],

            'country_code' => ['nullable', 'string', 'size:2', Rule::in(CountryCode::values())],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'street_number' => ['nullable', 'string', 'max:255'],
        ];
    }
}