<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'upc' => ['required', 'string', 'min:6', 'max:32', 'regex:/^[0-9A-Za-z]+$/'],
        ];
    }
}
