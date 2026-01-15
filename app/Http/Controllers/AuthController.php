<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->login);

        // Determine if login is email or employee_id
        $loginType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'employee_id';

        // Attempt to find user
        $user = User::where($loginType, $loginInput)->first();

        // Check if user exists
        if (!$user) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials do not match our records.'],
            ]);
        }

        // Check if user is approved
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'login' => ['Your account is pending approval. Please contact your upline.'],
            ]);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials do not match our records.'],
            ]);
        }

        // Log the user in
        Auth::login($user, $request->filled('remember'));

        // Log activity
        ActivityLog::logActivity(
            'login',
            $user->id,
            $user->id,
            'User logged in'
        );

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Log activity
        ActivityLog::logActivity(
            'logout',
            auth()->id(),
            auth()->id(),
            'User logged out'
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
