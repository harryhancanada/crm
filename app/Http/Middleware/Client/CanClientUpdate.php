<?php

namespace App\Http\Middleware\Client;

use Closure;

class CanClientUpdate
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
        if (!auth()->user()->can('client-update')) {
            Session()->flash('flash_message_warning', '您没有权限更新学生信息');
            return redirect()->route('clients.index');
        }
        return $next($request);
    }
}
