<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use App\Models\User;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $userowner = Role::where('name','owner')->first();

        if($user && $user->role_id === $userowner->id){
            return $next($request);
        }
        return response()->json([
            'Message' => 'Hanya User owner yang dapat mengakses EndPoint Ini',
        ], 401);
    }
}
