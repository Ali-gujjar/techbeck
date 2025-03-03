<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\Traits\ResponseTrait;

class StoreSignalRequest extends FormRequest
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
            'instrument' => 'required|string|max:255',
            'order_type_id' => 'required|integer|exists:order_types,id',
            'price' => 'nullable|numeric',
            'sl' => 'nullable|numeric',
            'tp1' => 'nullable|numeric',
            'tp2' => 'nullable|numeric',
            'tp3' => 'nullable|numeric',
            'date_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    $dateTime = \DateTime::createFromFormat('d M, Y H:i', $value);
                    if ($dateTime === false) {
                        $fail('The date time field must match the format d M, Y H:i.');
                    }
                },
            ],
            'status' => ['required', Rule::in(['Enable', 'Disabled'])],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trading_chart_link' => 'nullable|url',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationErrorResponse($validator->errors()->toArray())
        );
    }
}
