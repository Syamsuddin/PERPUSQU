<?php

namespace App\Modules\Circulation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barcode' => 'required|string|min:3|max:100',
            'returned_condition_id' => 'nullable|integer|exists:item_conditions,id',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
