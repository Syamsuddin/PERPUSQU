<?php
namespace App\Modules\MasterData\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudyProgramRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('studyProgram')?->id ?? $this->route('study_program')?->id;
        return [
            'faculty_id' => 'required|integer|exists:faculties,id',
            'code' => "required|string|min:2|max:50|alpha_dash|unique:study_programs,code,{$id}",
            'name' => 'required|string|min:2|max:150',
            'is_active' => 'nullable|boolean',
        ];
    }
}
