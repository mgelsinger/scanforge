<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransmutationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'times' => ['nullable', 'integer', 'min:1', 'max:99'],
        ];
    }
}
