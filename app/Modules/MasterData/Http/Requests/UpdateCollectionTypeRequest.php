<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('collectionType')?->id ?? $this->route('collection_type')?->id;
        return [
            'code' => "required|string|min:2|max:50|alpha_dash|unique:collection_types,code,{$id}",
            'name' => "required|string|min:2|max:150|unique:collection_types,name,{$id}",
            'is_active' => 'nullable|boolean',
        ];
    }
}
