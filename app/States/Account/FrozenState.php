<?php
namespace App\States\Account;

class FrozenState extends AccountState
{
    public function canDeposit(): bool { return true; }
    public function canWithdraw(): bool { return false; }
    public function canTransfer(): bool { return false; }
    public function canAddChild(): bool { return false; }
    public function canRemoveChild(): bool { return false; }

    public function activate(): void
    {
        $this->changeState(ActiveState::class, 'active');
    }

    public function getName(): string { return 'مجمد'; }
    public function getDescription(): string { return 'الحساب مجمد، يسمح بالإيداع فقط'; }
}
