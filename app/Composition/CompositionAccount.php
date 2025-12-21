<?php
namespace App\Composition;

use App\Models\Account;

class CompositeAccount implements AccountInterface
{
    protected Account $model;
    protected array $children = [];

    public function __construct(Account $account)
    {
        $this->model = $account;
        // يبني مصفوفة من الكائنات المغلفة للأبناء
        foreach ($account->children as $childModel) {
            $this->children[] = new LeafAccount($childModel);
        }
    }

    public function withdraw(float $amount): void
    {
        $count = count($this->children) + 1; // الرئيسي + الأبناء
        $share = $amount / $count;

        // نتحقق أولًا أن كل حساب لديه رصيد كافٍ (بسيط هنا)
        if ($this->model->balance < $share) {
            throw new \Exception("Insufficient funds in parent for its share");
        }

        // سحب من الحساب الرئيسي
        $this->model->balance -= $share;
        $this->model->save();

        // وسحب من كل طفل
        foreach ($this->children as $child) {
            $child->withdraw($share);
        }
    }

    public function deposit(float $amount): void
    {
        $count = count($this->children) + 1;
        $share = $amount / $count;

        $this->model->balance += $share;
        $this->model->save();

        foreach ($this->children as $child) {
            $child->deposit($share);
        }
    }

    public function getBalance(): float
    {
        $total = (float)$this->model->balance;
        foreach ($this->children as $child) {
            $total += $child->getBalance();
        }
        return $total;
    }

    public function getId(): int
    {
        return $this->model->id;
    }
}
