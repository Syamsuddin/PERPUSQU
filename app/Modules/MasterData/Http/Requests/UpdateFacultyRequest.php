<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFacultyRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('faculty')?->id;
        return [
            'code' => "required|string|min:2|max:50|alpha_dash|unique:faculties,code,{$id}",
            'name' => "required|string|min:2|max:150|unique:faculties,name,{$id}",
            'is_active' => 'nullable|boolean',
        ];
    }
}
