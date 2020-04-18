<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AltImagesProduct
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
        $services = User::getPermissions();
        $is_admin = User::checkIfAdmin();
        if(!(in_array("alternative_images", $services)) && $is_admin === false){
            return redirect('/admin/dashboard')->with('flash_message_error','You do not have permission to access this section');
        }
        return $next($request);
    }
}