<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\LoginHistory;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    /**
     * Show Login Page
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Login User
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {

            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->status !== 'active') {

            Auth::logout();

            return back()
                ->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the administrator.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $agent = new Agent();

        LoginHistory::create([
            'user_id'    => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser'    => $agent->browser(),
            'platform'   => $agent->platform(),
            'device'     => $agent->isDesktop()
                ? 'Desktop'
                : ($agent->isTablet() ? 'Tablet' : 'Mobile'),
            'login_at'   => now(),
            'status'     => 'login',
        ]);

        return redirect()->intended('/dashboard');
    }

    /**
     * Show Register Page
     */
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Register User
     */
    public function registerStore(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email',

            'password' => 'required|min:8|confirmed',

        ]);

        User::create([

            'name' => $validated['name'],

            'email' => $validated['email'],

            'role' => 'user',

            'status' => 'active',

            // Automatically hashed by User model cast
            'password' => $validated['password'],

        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful. Please login.');
    }

    /**
     * Logout User
     */
    // public function logout(Request $request)
    // {
    //     Auth::logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     return redirect()->route('login');
    // }

    public function logout(Request $request)
    {
        LoginHistory::where('user_id', Auth::id())
            ->whereNull('logout_at')
            ->latest()
            ->first()?->update([
                'logout_at' => now(),
                'status'    => 'logout',
            ]);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


    

    /**
     * Show Forgot Password Page
     */
    public function forgotPassword()
    {
        return view('auth.forgotpassword');
    }

    /**
     * Send Password Reset Link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors([
                'email' => __($status),
            ]);
    }

    /**
     * Show Reset Password Page
     */
    public function resetPassword(Request $request, string $token)
    {
        return view('auth.resetpassword', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password) {

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()
            ->route('login')
            ->with('success', __('Your password has been reset successfully.'))
            : back()
            ->withErrors([
                'email' => [__($status)],
            ]);
    }
}