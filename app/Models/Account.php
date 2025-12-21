<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\States\Account\{
    AccountStateInterface,
    ActiveState,
    FrozenState,
    SuspendedState,
    ClosedState
};

class Account extends Model
{
    use HasFactory;
    protected $guarded=[];
public function user(){
    return  $this->belongsTo(Account::class);
}
    public function parent(){
        return  $this->belongsTo(Account::class,'parent_id');
    }

    public function children(){
        return  $this->hasMany(Account::class,'parent_id');
    }
    //علاقة احفاد
    public function getStateAttribute(): AccountStateInterface
    {
        return match ($this->status) {
            'active'    => new ActiveState($this),
            'frozen'    => new FrozenState($this),
            'suspended' => new SuspendedState($this),
            'closed'    => new ClosedState($this),
            default     => new ActiveState($this),
        };
    }

    /**
     * Setter بسيط لتغيير الحالة - فقط 3 أسطر!
     */
    public function setState(AccountStateInterface $newState): void
    {
        $this->status = $newState->getName();
        $this->save();
    }

    /**
     * طرق مساعدة بسيطة
     */
    public function canDeposit(): bool
    {
        return $this->state->canDeposit();
    }

    public function canWithdraw(): bool
    {
        return $this->state->canWithdraw();
    }

    public function canTransfer(): bool
    {
        return $this->state->canTransfer();
    }

    public function canAddChild(): bool
    {
        return $this->state->canAddChild();
    }

    public function canRemoveChild(): bool
    {
        return $this->state->canRemoveChild();
    }


}
