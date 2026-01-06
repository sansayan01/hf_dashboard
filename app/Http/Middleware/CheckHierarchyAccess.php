<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHierarchyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If no user is authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user's account is active
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is pending approval or inactive.');
        }

        // Check if office in charge has expired
        if ($user->isOfficeInChargeExpired()) {
            $user->update([
                'is_office_in_charge' => false,
                'office_in_charge_type' => null,
                'office_in_charge_end_date' => null,
            ]);
        }

        return $next($request);
    }
}
