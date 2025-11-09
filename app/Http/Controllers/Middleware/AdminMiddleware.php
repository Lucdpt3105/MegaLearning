<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
{
    if (!Auth::check()) {
        return redirect()->route('admin.login');
    }

    $u = Auth::user();

    if (!isset($u->role) || (string)$u->role !== 'admin') {
        abort(403, 'Bạn không có quyền truy cập khu vực quản trị.');
    }
    if (property_exists($u, 'status') && $u->status !== 'active') {
        abort(403, 'Tài khoản không hoạt động.');
    }

    return $next($request);
}
}
