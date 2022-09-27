<?php

namespace App\Http\Middleware;

use Closure;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null, $permission = null)
    {
        if($role){
            $hrole = false;
            $role = explode("|",$role);
            
            foreach ($role as $krey => $vralue) {
                if($request->user()->hasRole($vralue)) {
                    $hrole = true;
                 }
            }
            if( $hrole == false){
                abort(404);
            }
            
             if($permission !== null && !$request->user()->can($permission)) {
                 abort(404);
             }
        }
        return $next($request);
    }
}