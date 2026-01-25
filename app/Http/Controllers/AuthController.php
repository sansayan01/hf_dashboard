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
        try {
            $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
            ]);

            $loginInput = trim($request->login);
            $loginType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'employee_id';

            $user = User::where($loginType, $loginInput)->first();

            if (!$user) {
                \Log::warning('Login failed: User not found', ['login' => $loginInput]);
                throw ValidationException::withMessages(['login' => ['The provided credentials do not match our records.']]);
            }

            if ($user->status !== 'active') {
                \Log::warning('Login failed: User not approved', ['id' => $user->id]);
                throw ValidationException::withMessages(['login' => ['Your account is pending approval. Please contact your upline.']]);
            }

            if (!Hash::check($request->password, $user->password)) {
                \Log::warning('Login failed: Incorrect password', ['id' => $user->id]);
                throw ValidationException::withMessages(['login' => ['The provided credentials do not match our records.']]);
            }

            Auth::login($user, $request->filled('remember'));

            ActivityLog::logActivity('login', $user->id, $user->id, 'User logged in');

            $request->session()->regenerate();

            \Log::info('Login successful', ['id' => $user->id, 'designation' => $user->designation]);

            return redirect()->intended(route('dashboard'));

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Critical login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'A system error occurred during login. Please contact support.');
        }
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
