<?php
namespace App\States\Account;

use App\Models\Account;
use Exception;

abstract class AccountState implements AccountStateInterface
{
    protected $account;

    public function __construct(Account $account) {
        $this->account = $account;
    }

    public function canDeposit(): bool { return false; }
    public function canWithdraw(): bool { return false; }
    public function canTransfer(): bool { return false; }
    public function canAddChild(): bool { return false; }
    public function canRemoveChild(): bool { return false; }

    public function activate(): void { throw new Exception("لا يمكن تفعيل من هذه الحالة"); }
    public function freeze(): void { throw new Exception("لا يمكن تجميد من هذه الحالة"); }
    public function suspend(): void { throw new Exception("لا يمكن تعليق من هذه الحالة"); }
    public function close(): void { throw new Exception("لا يمكن إغلاق من هذه الحالة"); }

    // ✅ تعديل بسيط فقط: استخدام setState بدلاً من التعديل المباشر
    protected function changeState(string $stateClass, string $status): void
    {
        $newState = new $stateClass($this->account);
        $this->account->setState($newState);
        $this->account->status = $status;
        $this->account->save();
    }
}
