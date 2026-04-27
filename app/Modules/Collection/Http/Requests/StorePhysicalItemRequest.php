<?php

namespace App\Modules\Collection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhysicalItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'bibliographic_record_id' => 'required|integer|exists:bibliographic_records,id',
            'rack_location_id' => 'nullable|integer|exists:rack_locations,id',
            'item_condition_id' => 'nullable|integer|exists:item_conditions,id',
            'barcode' => 'required|string|min:3|max:100|unique:physical_items,barcode',
            'inventory_code' => 'nullable|string|min:3|max:100|unique:physical_items,inventory_code',
            'acquisition_date' => 'nullable|date',
            'item_status' => 'required|string|in:available,damaged,repair,inactive',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'barcode.unique' => 'Barcode sudah digunakan.',
            'inventory_code.unique' => 'Kode inventaris sudah digunakan.',
            'item_status.in' => 'Item baru hanya boleh berstatus: Tersedia, Rusak, Perbaikan, atau Nonaktif.',
        ];
    }
}
