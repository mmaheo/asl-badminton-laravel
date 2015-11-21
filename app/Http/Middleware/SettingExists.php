<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;

class SettingExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $setting = Setting::first();

        if ($setting !== null)
        {
            return $next($request);
        }

        return redirect()->back()->with('error', "Il faut d'abord créer la page paramètre !");
    }
}
