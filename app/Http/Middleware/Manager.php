<?php

namespace App\Http\Middleware;

use Closure;
use App\Group;
use Auth;

class Manager
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
        public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $group = Group::where('id',Auth::user()->group_id)->pluck('group_name')->first();
            if($group = "Asst.Manager"){
                return redirect()->back();
            }
        }
        return $next($request);
    }
    }
}
