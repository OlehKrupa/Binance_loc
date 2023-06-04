<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Currency;

class CheckCryptocurrencyCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $selectedCryptocurrenciesCount = Auth::user()->currencies()->count();

            if ($selectedCryptocurrenciesCount < 1) {
                return redirect()->route('preferences');
            }
        }

        return $next($request);
    }
}
