<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Services\UserService;

class CheckCryptocurrencyCount
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $selectedCryptocurrenciesCount = $this->userService->getUserCurrencies(Auth::user())->count();

            if ($selectedCryptocurrenciesCount < 1) {
                return redirect()->route('preferences');
            }
        }

        return $next($request);
    }
}
