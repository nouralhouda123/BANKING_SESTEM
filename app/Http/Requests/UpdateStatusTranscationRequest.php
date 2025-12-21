<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusTranscationRequest extends FormRequest
{
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
            'status' => [
                'required',
                Rule::in(['rejected', 'approved'])
            ],
            'reason' => 'nullable|string|max:500',
//
        ];

    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'حالة المعاملة مطلوبة',
            'status.in' => 'الحالة المحددة غير صالحة. يجب أن تكون واحدة من: , approved, rejected, , ',
            'reason.max' => 'سبب التعديل يجب ألا يتجاوز 500 حرف',
        ];
    }
}
