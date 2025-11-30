<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can update users
        return $this->user() && $this->user()->role->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('users', 'name')->ignore($this->route('user'))
            ],
            'email' => 'sometimes|email|max:100',
            'password' => 'sometimes|string|min:8|max:255',
            'role_id' => 'sometimes|exists:roles,id',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'This username is already taken',
            'email.email' => 'Please provide a valid email address',
            'password.min' => 'Password must be at least 8 characters',
            'role_id.exists' => 'Selected role does not exist',
        ];
    }
}
