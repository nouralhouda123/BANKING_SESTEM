<?php

namespace App\DTO;

use App\Models\Transaction;

class TransferResult
{
    public function __construct(
        public string $status,
        public string $message,
        public Transaction $transaction
    ) {}
}
