<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduledTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'from_account_id' => 'nullable|exists:accounts,id',
            'to_account_id' => 'nullable|exists:accounts,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:deposit,withdraw,transfer',
            'scheduled_at' => 'required|date|after_or_equal:today',
            'frequency' => 'nullable|in:daily,weekly,monthly',
        ];
    }
}
