<?php

namespace App\Modules\Circulation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RenewLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
