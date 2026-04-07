<?php

namespace App\Http\Requests\Personnel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexPersonnelRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'company_id' => ['nullable', 'integer', 'min:1', 'exists:companies,id'],
            'occupation_id' => ['nullable', 'integer', 'min:1', 'exists:occupations,id'],
        ];
    }
}
