<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // مهم جداً حتى يسمح بتمرير الطلب
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6',

            'government_agencie_id' => 'required|exists:government_agencies,id',

            // رقم الجوال: يبدأ بـ 09 وطوله 10 أرقام
            'phone' => [
                'required',
                'regex:/^09\d{8}$/',
                'unique:users,phone',
            ],

            // الرقم الوطني: أرقام فقط
            'National_Identifier' => [
                'required',
                'digits_between:1,50',
                'regex:/^[0-9]+$/',
                'unique:users,National_Identifier',
            ],

            'permissions'   => 'array',
            'permissions.*' => 'required|string|exists:permissions,name',
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            ResponseHelper::Validate(
                'Validation error.',
                422,
                $validator->errors()->toArray()
            )
        );
    }

}
