<?php

namespace App\Http\Requests\Occupations;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOccupationRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var \App\Models\Occupation|null $occupation */
        $occupation = $this->route('occupation');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('occupations', 'name')->ignore($occupation?->id),
            ],
        ];
    }
}