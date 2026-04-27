<?php

namespace App\Modules\Collection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePhysicalItemStatusRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'new_status' => 'required|string|in:available,loaned,damaged,lost,repair,inactive',
            'reason' => 'nullable|string|max:1000',
        ];
    }
}
