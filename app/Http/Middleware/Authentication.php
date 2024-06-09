<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class Authentication //Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_current = Auth::user();

        $user = User::where('token', $user_current->header)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        return $next($request);
    }
}
