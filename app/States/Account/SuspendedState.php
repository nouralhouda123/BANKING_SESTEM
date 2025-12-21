<?php
namespace App\States\Account;

class SuspendedState extends AccountState
{
    public function canDeposit(): bool { return false; }
    public function canWithdraw(): bool { return false; }
    public function canTransfer(): bool { return false; }

    public function activate(): void
    {
        $this->changeState(ActiveState::class, 'active');
    }

    public function getName(): string { return 'موقوف'; }
    public function getDescription(): string { return 'الحساب موقوف، جميع العمليات محظورة'; }
}
