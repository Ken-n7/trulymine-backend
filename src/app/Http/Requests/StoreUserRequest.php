<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can create users
        return $this->user() && $this->user()->role->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:8|max:255',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'User name is required',
            'name.unique' => 'This username is already taken',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'role_id.required' => 'User role is required',
            'role_id.exists' => 'Selected role does not exist',
        ];
    }
}
