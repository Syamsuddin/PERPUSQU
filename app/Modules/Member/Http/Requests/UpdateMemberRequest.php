<?php

namespace App\Modules\Member\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberId = $this->route('member')?->id ?? $this->route('member');

        return [
            'member_number' => 'required|string|min:3|max:100|unique:members,member_number,'.$memberId,
            'member_type' => 'required|string|in:student,lecturer,staff,alumni,guest',
            'identity_number' => 'nullable|string|max:100|unique:members,identity_number,'.$memberId,
            'name' => 'required|string|min:3|max:200',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:50',
            'faculty_id' => 'nullable|integer|exists:faculties,id',
            'study_program_id' => 'nullable|integer|exists:study_programs,id',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
