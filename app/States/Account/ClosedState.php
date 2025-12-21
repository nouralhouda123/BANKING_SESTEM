<?php
namespace App\States\Account;

class ClosedState extends AccountState
{
    public function canDeposit(): bool { return false; }
    public function canWithdraw(): bool { return false; }
    public function canTransfer(): bool { return false; }

    public function canAddChild(): bool { return false; }
    public function canRemoveChild(): bool { return false; }

    public function getName(): string { return 'closed'; }
    public function getDescription(): string { return 'الحساب مغلق بشكل نهائي'; }
}
