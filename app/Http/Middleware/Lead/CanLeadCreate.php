<?php

namespace App\Http\Middleware\Lead;

use Closure;

class CanLeadCreate
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
        if (!auth()->user()->can('lead-create')) {
            Session()->flash('flash_message_warning', '您没有创建咨询的权限');
            return redirect()->route('leads.index');
        }
        return $next($request);
    }
}
