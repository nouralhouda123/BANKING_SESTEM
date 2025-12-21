<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EmployeeRequest;
use App\Services\EmployeeService;
use App\Services\UserService;
use Illuminate\Http\Request;

class usercontroller extends Controller
{
    private $service;

    public function __construct(
        UserService $service
    ) {
        $this->service = $service;
    }
    //اضافة ابن
    public function add_client(EmployeeRequest $request)
    {
        $data=[];
        try{
            $data=$this->service->add_client($request);
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }

//عرض عملاء

    public function showclient( )
    {
        $data=[];
        try{
            $data=$this->service->getusers();
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }


    //اضافة مدير
    public function add_director(EmployeeRequest $request)
    {
        $data=[];
        try{
            $data=$this->service->add_director($request);
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }



    //اضافة موظف
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



    //عرض الموظفين
    public function showAllemployee( )
    {
        $data=[];
        try{
            $data=$this->service->getAllEmployee();
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }



    //عرض العملاء
    public function showAlldirector( )
    {
        $data=[];
        try{
            $data=$this->service->getAlldirector();
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }



    ////عرض العملاء
    public function showallClient( )
    {
        $data=[];
        try{
            $data=$this->service->getallClient();
            return ResponseHelper::Success($data['user'],$data['message']);}
        catch( \Throwable $th)
        {
            $message=$th->getMessage();
            return ResponseHelper::Error($data,$message);}
    }



}
