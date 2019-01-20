<?php

namespace App\Http\Middleware;

use Closure;

class UserIsAuthorizedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->article->user_id != user()->id)
        {
            return response()->json(['not_authorized'], 401);
        }
        return $next($request);
    }
}
