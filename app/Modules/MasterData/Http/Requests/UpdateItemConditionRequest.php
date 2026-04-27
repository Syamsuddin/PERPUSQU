<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemConditionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('itemCondition')?->id ?? $this->route('item_condition')?->id;
        return [
            'code' => "required|string|min:2|max:50|alpha_dash|unique:item_conditions,code,{$id}",
            'name' => "required|string|min:2|max:150|unique:item_conditions,name,{$id}",
            'severity_level' => 'required|integer|min:1|max:10',
            'is_active' => 'nullable|boolean',
        ];
    }
}
