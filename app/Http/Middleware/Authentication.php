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

        // dd('middleware called');
        $token = $request->header('Authorization');

        // dd($token);
        $auth = true;
        if (!$token) {
            $auth = false;
        }

        $user = User::where('token', $token)->first();
        if (!$user) {
            $auth = false;
        } else {
            Auth::login($user);
        }

        if (!$auth) {
            return response()->json([
                "errors" => [
                    "message" => "Unauthorized"
                ],

            ], 401);
        }


        return $next($request);
    }
}
