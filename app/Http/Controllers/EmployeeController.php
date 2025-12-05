<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use App\Http\Requests\EmployeeRequest;
use App\Services\EmployeeService;
use App\Services\TestService;
use Illuminate\Http\Request;
class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeService $service
    ) {}
    public function index()
    {

    }
    /**
     * Show the form for creating a new resource.
     */
    public function add_employee(EmployeeRequest $request)
    {
        $data=[];
        try{
            $data=$this->service->add_employee($request);
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
