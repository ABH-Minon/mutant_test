<?php

namespace App\Http\Middleware;

use Closure;

class UserType
{
    public function handle($request, Closure $next, $type)
    {
        $userType = $request->session()->get('user_type');

        if ($userType !== $type) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

