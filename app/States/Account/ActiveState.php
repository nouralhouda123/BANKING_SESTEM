<?php
namespace App\States\Account;

use Exception;

class ActiveState extends AccountState
{
    public function canDeposit(): bool { return true; }
    public function canWithdraw(): bool { return true; }
    public function canTransfer(): bool { return true; }
    public function canAddChild(): bool { return true; }
    public function canRemoveChild(): bool { return true; }

    public function freeze(): void
    {
        $this->changeState(FrozenState::class, 'frozen');
    }

    public function suspend(): void
    {
        $this->changeState(SuspendedState::class, 'suspended');
    }
    public function close(): void
    {
        if ($this->account->balance > 0) {
            throw new Exception("لا يمكن إغلاق حساب برصيد إيجابي");
        }


        // تغيير الحالة إلى ClosedState
        $this->changeState(\App\States\Account\ClosedState::class, 'closed');
    }

    public function getName(): string { return 'نشط'; }
    public function getDescription(): string { return 'الحساب نشط وجاهز لجميع العمليات'; }
}
