<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotAdmin
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
        if (!auth()->user()->hasRole('administrator')) {
            Session()->flash('flash_message_warning', '您不是管理员，无权查看此页。');
            return redirect()->back();
        }
        return $next($request);
    }
}
