<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $userOwner = Roles::where('name', 'owner')->first();
        if ($user && $user->role_id === $userOwner->id) {
            return $next($request);
        }
        return response()->json(
            ['error' => 'Anda tidak bisa mengakses halaman ini']
            , 401);
    }
}
