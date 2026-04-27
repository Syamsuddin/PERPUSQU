<?php

namespace App\Modules\Collection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhysicalItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $itemId = $this->route('item')?->id ?? $this->route('item');
        return [
            'rack_location_id' => 'nullable|integer|exists:rack_locations,id',
            'item_condition_id' => 'nullable|integer|exists:item_conditions,id',
            'barcode' => 'required|string|min:3|max:100|unique:physical_items,barcode,' . $itemId,
            'inventory_code' => 'nullable|string|min:3|max:100|unique:physical_items,inventory_code,' . $itemId,
            'acquisition_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
