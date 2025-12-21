<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'type' => 'nullable|in:savings,checking,loan,investment',
            'account_type' => 'nullable|in:composite,leaf',
            'balance' => 'nullable|numeric|min:0',
            'parent_id' => 'nullable|exists:accounts,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:active,blocked',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.in' => 'Account type must be one of: composite,leaf.',
            'account_type.in' => 'Account type must be one of: savings, checking, loan, investment.',
            'parent_id.exists' => 'Parent account does not exist.',
            'balance.numeric' => 'Balance must be a valid number.',
            'balance.min' => 'Balance cannot be negative.',
            'status.in' => 'Status must be either active or blocked.',
        ];
    }
}
