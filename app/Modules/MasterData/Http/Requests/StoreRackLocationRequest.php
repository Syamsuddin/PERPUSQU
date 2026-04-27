<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreRackLocationRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code' => 'required|string|min:2|max:50|unique:rack_locations,code',
            'name' => 'required|string|min:2|max:150',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ];
    }
}
