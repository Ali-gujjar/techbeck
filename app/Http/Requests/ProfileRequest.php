<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ResponseTrait;

class ProfileRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'emirates_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'passport_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'company_name' => 'nullable|string',
            'trading_license_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationErrorResponse($validator->errors()->toArray())
        );
    }
}
