<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreClassificationRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer|exists:classifications,id',
            'code' => 'required|string|min:1|max:50|unique:classifications,code',
            'name' => 'required|string|min:2|max:200',
            'is_active' => 'nullable|boolean',
        ];
    }
}
