<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code' => 'required|string|min:2|max:50|alpha_dash|unique:faculties,code',
            'name' => 'required|string|min:2|max:150|unique:faculties,name',
            'is_active' => 'nullable|boolean',
        ];
    }
}
