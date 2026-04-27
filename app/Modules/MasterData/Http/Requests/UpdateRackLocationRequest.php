<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRackLocationRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('rackLocation')?->id ?? $this->route('rack_location')?->id;
        return [
            'code' => "required|string|min:2|max:50|unique:rack_locations,code,{$id}",
            'name' => 'required|string|min:2|max:150',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ];
    }
}
