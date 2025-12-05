<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RoleBasedThrottle
{
    public function handle(Request $request, Closure $next)
    {
        $role = $request->user()?->roles->first()?->name ?? 'admin';

        // تحديد الحد لكل دور
        $limits = [
            'admin' => 2,
            'employee' => 10,
          //  'guest' => 5
        ];

        $maxAttempts = $limits[$role] ?? 5;

        // مفتاح الفريدة لكل مستخدم أو IP
        $key = "rate:{$role}:" . ($request->user()?->id ?? $request->ip());

        // تحقق من تجاوز الحد
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many requests. Try again in $seconds seconds.",
                'retry_after' => $seconds
            ], 429);
        }

        // تسجيل محاولة جديدة
        RateLimiter::hit($key, 60); // مدة 60 ثانية لكل محاولة

        return $next($request);
    }
}
