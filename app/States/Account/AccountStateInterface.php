<?php
namespace App\States\Account;

interface AccountStateInterface
{
    public function canDeposit(): bool;
    public function canWithdraw(): bool;
    public function canTransfer(): bool;
    public function canAddChild(): bool;
    public function canRemoveChild(): bool;

    public function activate(): void;
    public function freeze(): void;
    public function suspend(): void;
    public function close(): void;

    public function getName(): string;
    public function getDescription(): string;
}
