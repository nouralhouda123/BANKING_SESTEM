<?php
namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ScheduledTransactionRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\UpdateStatusTranscationRequest;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
use App\Services\transcationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    private $service;

    public function __construct(
        transcationService $service
    ) {
        $this->service = $service;
    }
    public function showallTranscation( )
    {
        $data=[];
        try{
            $data=$this->service->getAll();
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }

    ////// عرض ممعاملات العميل
    public function showTransaction( ): \Illuminate\Http\JsonResponse
    {
            $data=$this->service->get();
return response()->json(['data'=>$data

]);}

    public function storeScheduled(ScheduledTransactionRequest $request)
    {
        $transaction = $this->service->createScheduledTransaction($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Scheduled transaction created successfully.',
            'transaction' => $transaction
        ]);
    }


    public function store(TransferRequest $request)
    {
        $result = $this->service->processTransfer($request);

        return response()->json([
            'status' => $result->status,
            'message' => $result->message,
            'transaction' => $result->transaction
        ], 201);

}    public function updateStatusTranscation(UpdateStatusTranscationRequest $request,int $id)
    {
        $transaction = $this->service->UpdateTransaction($request,$id);
        return response()->json([
            'status' => 'success',
            'message' => "Transaction {$id} approved and executed.",
            'transaction' => $transaction
        ]);
    }
}
