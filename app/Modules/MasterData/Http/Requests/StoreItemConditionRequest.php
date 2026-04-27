<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemConditionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code' => 'required|string|min:2|max:50|alpha_dash|unique:item_conditions,code',
            'name' => 'required|string|min:2|max:150|unique:item_conditions,name',
            'severity_level' => 'required|integer|min:1|max:10',
            'is_active' => 'nullable|boolean',
        ];
    }
}
