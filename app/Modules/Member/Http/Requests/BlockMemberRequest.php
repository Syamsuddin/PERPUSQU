<?php

namespace App\Modules\Member\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blocked_reason' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'blocked_reason.required' => 'Alasan pemblokiran wajib diisi.',
            'blocked_reason.min' => 'Alasan pemblokiran minimal 3 karakter.',
        ];
    }
}
