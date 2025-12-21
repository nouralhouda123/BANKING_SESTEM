<?php
namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateAccountStatusRequest;
use App\Http\Requests\UpdateStatusTranscationRequest;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected AccountService $service;

    public function __construct(AccountRepository $repo)
    {
        $this->service = new AccountService($repo);
    }
    public function createMainAccount(AccountRequest $request)
    {
        $userId = Auth::id();
        $account = $this->service->createMainAccount($userId, $request);
        return response()->json(['message' => 'Main account created', 'data' => $account]);
    }
//انشاء حساب فرعي للابن
//اولا لازم انشئ  اضيف ابني لجدول user حتى يقدر بعدين ييفوت لابنه ويضيف اين له
    public function addChildAccount(AccountRequest $request)
    {
        try {
            $child = $this->service->addSubAccountToComposite($request);
            return response()->json([
                'status' => 'success',
                'message' => 'Child account added successfully',
                'data' => $child
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);
        }
    }
//تعديل حساب
public function updateaccount(AccountRequest $request){
    try {
        $account =$this->service->updateaccount($request);
        return response()->json([
            'status' => 'success',
            'message' => 'تم تعديل حساب بنجاح',
            'data' => $account
        ]);
    } catch (\Exception $ex) {
        return response()->json([
            'status' => 'failed',
            'message' => $ex->getMessage()
        ], 400);

}
}
    public function updateStatusAccount(UpdateAccountStatusRequest $request, $id){
        try {
            $account =$this->service->updateStatusAccount($request,$id);
            return response()->json([
                'status' => 'success',
                'message' => 'تم تعديل حساب بنجاح',
                'data' => $account
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);

        }
    }
//تعديل حالة حساب للموظف مغلق نشط
    ////عرض حسابات
    /// مع الابناء وابناء الابناء
//الكل
//اغلاق حساب
    public function closeAccount(AccountRequest $request){
        try {
            $account = $this->service->closeAccount($request);
            return response()->json([
                'status' => 'success',
                'message' => 'تم اغلاق حساب بنجاح ',
                'data' => $account
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);

        }}
//عرض حسابه

    public function getAllAccount( ){
        try {
            $account = $this->service->getAllaccount();
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $account
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);

        }}

        public function show( ){
            try {
                $account = $this->service->get();
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم عرض  حساب بنجاح',
                    'data' => $account
                ]);
            } catch (\Exception $ex) {
                return response()->json([
                    'status' => 'failed',
                    'message' => $ex->getMessage()
                ], 400);

            }}
    //عرض شجرة عائلة
    //لاحقا
    //عرض حسابات الابناء
    public function showAccountchildrenForEm( $id){
        try {
            $account = $this->service->getAllchildrenForEm($id);
            return response()->json([
                'status' => 'success',
                'data' => $account
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);

        }}
    public function showAccountChildren( ){
        try {
            $account = $this->service->getAllChildren();
            return response()->json([
                'status' => 'success',
              //  'message' => '',
                'data' => $account
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);

        }}

    //عرض حسابات ابناء الابناء
    public function showAllGrandSon(){
        try {
            $account = $this->service->getAllGrandson();
            return response()->json([
                'status' => 'success',
                'message' => 'تم عرض  حساب بنجاح',
                'data' => $account
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);

        }}
//
    //عرض حسابات ابناء الابناء
    public function showAllGrandSonForEmployee($id){
        try {
            $account = $this->service->getAllGrandsonForEm($id);
            return response()->json([
                'status' => 'success',
                'message' => 'تم عرض  حساب بنجاح',
                'data' => $account
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'failed',
                'message' => $ex->getMessage()
            ], 400);

        }}

// عرض رصيد كلي
            public function showTotal(AccountRequest $request){
                try {
                    $account = $this->service->showTotal();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'تم عرض رصيد',
                        'data' => $account
                    ]);
                } catch (\Exception $ex) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => $ex->getMessage()
                    ], 400);

                }}}



