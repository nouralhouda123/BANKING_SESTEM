<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CitizenRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class CitizenAuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register a new citizen and send email verification
     */
    public function register(CitizenRegisterRequest $request)
    {
        // استدعاء Service لتسجيل المستخدم
        $result = $this->userService->register($request->validated());

        // إرجاع JSON response مع الرسالة والكود وبيانات المستخدم
        return response()->json([
            'message' => 'Citizen registered successfully. Please verify your email.',
            'verification_code' => $result['verification_code'], // مؤقتاً للاختبار فقط
            'data' => $result['user']
        ], 201);
    }
}
