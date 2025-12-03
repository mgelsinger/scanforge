<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'units' => ['array', 'max:5'],
            'units.*.id' => ['nullable', 'integer', 'exists:forged_units,id'],
            'units.*.position' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
}
