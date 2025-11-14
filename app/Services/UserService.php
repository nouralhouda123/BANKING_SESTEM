<?php

namespace App\Services;

use App\Mail\EmailVerificationMail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;

class UserService
{
    public function register(array $data)
    {
        // إنشاء المستخدم
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'national_id'=> $data['national_id'],
            'password'   => bcrypt($data['password']),
        ]);

        $user->assignRole('citizen');

        // توليد رمز تحقق عشوائي
        $verificationCode = rand(100000, 999999);

        // حفظ رمز التحقق في جدول email_verifications
        EmailVerification::create([
            'email' => $user->email,
            'code' => $verificationCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        // إرسال الايميل
        Mail::to($user->email)->send(new EmailVerificationMail($verificationCode));

        // إعادة البيانات للكنترولر
        return [
            'user' => $user->load('roles'),
            'verification_code' => $verificationCode,
        ];
    }





    // تابع لتأكيد البريد الإلكتروني
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $verification = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Invalid verification code.'
            ], 400);
        }

        if ($verification->expires_at < now()) {
            return response()->json([
                'message' => 'Verification code has expired.'
            ], 400);
        }

        // تفعيل المستخدم
        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();

        // حذف رمز التحقق بعد الاستخدام
        $verification->delete();

        return response()->json([
            'message' => 'Email verified successfully.',
            'user' => $user
        ]);
    }

}
