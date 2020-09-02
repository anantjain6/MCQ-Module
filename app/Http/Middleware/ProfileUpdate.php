<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ProfileUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if (!session()->has('profilecheck')) {
        //     $adm_yr = substr(Auth::user()->admno, 0, 5);
        //     $cur_yr = date("Y") - 2000;
        //     if ($cur_yr > $adm_yr && Auth::user()->rollno == null) {
        //         return redirect('student/profile/new')
        //             ->with(['msg' => 'You need to mention your universty roll number.', 'class' => 'alert-primary']);
        //     } else {
        //         session(['profilecheck' => 1]);
        //         return $next($request);
        //     }
        // } else {
            return $next($request);
        // }
    }
}
