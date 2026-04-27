<?php

namespace App\Modules\Circulation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id' => 'required|integer|exists:members,id',
            'barcode' => 'required|string|min:3|max:100',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.required' => 'Pilih anggota terlebih dahulu.',
            'barcode.required' => 'Masukkan barcode item.',
        ];
    }
}
