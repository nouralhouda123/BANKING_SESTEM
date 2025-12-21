<?php
namespace App\Services;

use App\Composition\AccountInterface;
use App\Composition\CompositionAccount;
use App\Composition\LeafAccount;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateStatusTranscationRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\GrandChildSimpleResource;
use App\Models\Account;
use App\Repositories\AccountRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    public AccountRepository $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    public function wrapAccount(Account $accountModel): AccountInterface
    {
        if ($accountModel->children->isEmpty()) {
            return new LeafAccount($accountModel);
        }
        return new CompositionAccount($accountModel);
    }
    public function getAllChildren()
    {
        $user = Auth::user();
        $userId = $user->id;
        $parentAccount = $this->repo->findByuser_id($userId);
        if (!$parentAccount) {
            throw new \Exception("Your main account was not found");
        }
        $childrenAccounts = $this->repo->findByParent_id($parentAccount->id);

        return $childrenAccounts;
    }

    public function getAllchildrenForEm($id)
    {
        // 1. الحصول على حساب الأب
        $parentAccount = $this->repo->findByuser_idForEm($id);

        // 2. التحقق من وجود حساب الأب
        if (!$parentAccount) {
            throw new \Exception("Your main account was not found");
        }

        // 3. الحصول على حسابات الأبناء
        $childrenAccounts = $this->repo->findByParent_id($parentAccount->id);

        return $childrenAccounts;
    }
  public function getAllGrandson()
{
    $user = Auth::user();
    $parentAccount = $this->repo->findByuser_id($user->id);

    if (!$parentAccount) {
        throw new \Exception('الحساب الرئيسي غير موجود');
    }

    $children = $this->repo->findByParent_id($parentAccount->id);

    if ($children->isEmpty()) {
        throw new \Exception('لا يوجد حسابات فرعية');
    }

    $grandChildren = collect();

    foreach ($children as $child) {
        $childGrandChildren = $this->repo->findByParent_id($child->id);

        if ($childGrandChildren->isNotEmpty()) {
            $grandChildren = $grandChildren->merge($childGrandChildren);
        }
    }

    if ($grandChildren->isEmpty()) {
        throw new \Exception('لا يوجد حسابات أحفاد');
    }

    // استخدام الـ Resource مباشرة
    return GrandChildSimpleResource::collection($grandChildren);
}//عرض حسابه

//
       public function getAllGrandsonForEm($id)
    {
        $parentAccount = $this->repo->findByuser_idForEm($id);

        if (!$parentAccount) {
            throw new \Exception('الحساب الرئيسي غير موجود');
        }

        $children = $this->repo->findByParent_id($parentAccount->id);

        if ($children->isEmpty()) {
            throw new \Exception('لا يوجد حسابات فرعية');
        }

        $grandChildren = collect();

        foreach ($children as $child) {
            $childGrandChildren = $this->repo->findByParent_id($child->id);

            if ($childGrandChildren->isNotEmpty()) {
                $grandChildren = $grandChildren->merge($childGrandChildren);
            }
        }

        if ($grandChildren->isEmpty()) {
            throw new \Exception('لا يوجد حسابات أحفاد');
        }

        // استخدام الـ Resource مباشرة
        return GrandChildSimpleResource::collection($grandChildren);
        }
        //عرض حسابه
    public function get(){
        $user = Auth::user();
        $userId=$user->id;
        $parentModel = $this->repo->find($userId);
        if (!$parentModel) throw new \Exception("your account not found");
        return $parentModel;
    }
    //عرض
    public function getAllAccount(){
        $parentModel = $this->repo->getAllaccount();
        return $parentModel;
    }
    public function closeAccount()
    {
        $user = Auth::user();
        $userId = $user->id;

        // 1️⃣ الحصول على الحساب الأب
        $parentModel = $this->repo->find($userId);
        if (!$parentModel) {
            throw new \Exception("Your account not found");
        }

        // 2️⃣ التأكد من أن الحساب يحتوي على كائن الحالة
        $state = $parentModel->state; // AccountState الحالي
        if (!$state) {
            throw new \Exception("Account state not set");
        }

        // 3️⃣ استدعاء دالة close() على الـ State
        $state->close();

        // 4️⃣ إرجاع الأبناء بعد الإغلاق (إذا أردت)
        return $parentModel;
    }

//اغلاق حساب
 //   public function closeAccount($request){
  ////      $user = Auth::user();
       // $userId=$user->id;
       // $parentModel = $this->repo->find($userId);
      //  if (!$parentModel) throw new \Exception("your account not found");
       // $this->repo->closeAccount($request,$parentModel);
     //   return $parentModel->children();
  //  }
//عرض رصيد
    public function showTotal(){
        $user = Auth::user();
        $userId=$user->id;
        $parentModel = $this->repo->find($userId);
        if (!$parentModel) throw new \Exception("your account not found");
        $account=$this->wrapAccount($parentModel);
        return $account->getBalance();
    }

    //
public function updateAccount($request){
    $user = Auth::user();
    $userId=$user->id;
    $parentModel = $this->repo->find($userId);
    if (!$parentModel) throw new \Exception("your account not found");
   $account= $this->repo->updateaccount($request,$parentModel);
return $account;
}
    public function createMainAccount(int $userId, AccountRequest $request): Account
    {
        if ($request['account_type'] === 'composite') {
            $request['balance'] = null;
        }

        return $this->repo->createMain($userId, $request);
    }

    public function addSubAccountToComposite(AccountRequest $request)
    {
        $user = Auth::user();
        $userId=$user->id;
        $parentModel = $this->repo->find($userId);
        if (!$parentModel) throw new \Exception("Parent account not found");
        if ($parentModel->account_type !== 'composite') {
            throw new \Exception("Parent account must be composite to add child");
        }
        if(!$parentModel->state->canAddChild()){
            throw new \Exception("you cannot add child account from this account because its status");

    }
        $subModel=  $this->repo->createSub($userId, $request,$parentModel->id);

        $parentComposite = $this->wrapAccount($parentModel);
        $subComposite = $this->wrapAccount($subModel);
        $parentComposite->addChild($subComposite);
        return $subModel;
     //   return new AccountResource($subModel->fresh('children'));
    }

////////////////
    //public function updateStatusAccount( $request ,$id ){
    //    $parentModel = $this->repo->findAccounts($id);
      //  if (!$parentModel) throw new \Exception("the  account not found");
     ///    $this->repo->updateStatusaccount($request,$parentModel);
    //     return $parentModel;
   // }

    public function updateStatusAccount($request, $id)
    {
        $account = $this->repo->findAccounts($id);
        if (!$account) {
            throw new \Exception("The account not found");
        }

        // اختيار الحالة الجديدة حسب الطلب
        switch ($request->status) {
            case 'active':
                $account->state->activate();
                break;
            case 'suspended':
                $account->state->suspend();
                break;
            case 'frozen':
                $account->state->freeze();
                break;
            case 'closed':
                $account->state->close();
                break;
            default:
                throw new \Exception("Invalid status");
        }

        return $account;
    }

}
