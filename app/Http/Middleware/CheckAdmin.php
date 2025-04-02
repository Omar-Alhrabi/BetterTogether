<?php

namespace App\Http\Middleware;


// app/Http/Middleware/CheckAdmin.php
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * التحقق من أن المستخدم هو مشرف
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect('login');
        }
        
        // التحقق من أن المستخدم هو مشرف
        // يمكن استخدام حقل is_admin أو Spatie Permission حسب الاحتياج
        if (!Auth::user()->is_admin) {
            return redirect('/')->with('error', 'ليس لديك صلاحية الوصول إلى لوحة التحكم');
        }
        
        return $next($request);
    }
}