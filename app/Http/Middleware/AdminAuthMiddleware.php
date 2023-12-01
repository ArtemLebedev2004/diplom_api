<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (!Gate::allows('isAdmin')) {

            return response()->json([
                "warning" => [
                    "code" => 403,
                    "message" => "Доступ для вашей группы запрещён"
                ]
            ], 403);

        } else {
            return $next($request);
        }
    }
}
