<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code' => 'required|string|min:2|max:20|unique:languages,code',
            'name' => 'required|string|min:2|max:100|unique:languages,name',
            'is_active' => 'nullable|boolean',
        ];
    }
}
