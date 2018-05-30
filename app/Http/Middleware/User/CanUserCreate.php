<?php

namespace App\Http\Middleware\User;

use Closure;

class CanUserCreate
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
        if (!auth()->user()->can('user-create')) {
            Session()->flash('flash_message_warning', '您没有新建顾问的权限');
            return redirect()->route('users.index');
        }
        return $next($request);
    }
}
