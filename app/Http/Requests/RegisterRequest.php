<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'country' => 'required|string',
            'contact_number' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:master,slave',
        ];
    }
}
