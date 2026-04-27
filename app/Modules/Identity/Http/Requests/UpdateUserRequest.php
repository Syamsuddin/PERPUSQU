<?php

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => 'required|string|min:3|max:150',
            'username' => "required|string|min:3|max:100|alpha_dash|unique:users,username,{$userId}",
            'email' => "required|email:rfc|max:150|unique:users,email,{$userId}",
            'is_active' => 'nullable|boolean',
        ];
    }
}
