<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CraftingActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipe_id' => ['required', 'integer', 'exists:recipes,id'],
            'target_unit_id' => ['nullable', 'integer', 'exists:forged_units,id'],
        ];
    }
}
