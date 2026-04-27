<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudyProgramRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'faculty_id' => 'required|integer|exists:faculties,id',
            'code' => 'required|string|min:2|max:50|alpha_dash|unique:study_programs,code',
            'name' => 'required|string|min:2|max:150',
            'is_active' => 'nullable|boolean',
        ];
    }
}
