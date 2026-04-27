<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('language')?->id;
        return [
            'code' => "required|string|min:2|max:20|unique:languages,code,{$id}",
            'name' => "required|string|min:2|max:100|unique:languages,name,{$id}",
            'is_active' => 'nullable|boolean',
        ];
    }
}
