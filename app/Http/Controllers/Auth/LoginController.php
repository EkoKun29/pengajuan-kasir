<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
     public function login()
    {
        return view('auth.login');
    }

    public function posts(Request $request)
    {
        // Rate limiting untuk mencegah brute force
        $this->throttle($request);
        
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Clear rate limiting setelah login berhasil
            $this->clearLoginAttempts($request);

            $role = Auth::user()->role;

            return redirect()->intended(match ($role) {
                'direktur'    => route('direktur.dashboard'),
                'keuangan' => route('keuangan.dashboard'),
                'admin' => route('admin.dashboard'),
            });
        }

        // Increment login attempts
        $this->incrementLoginAttempts($request);

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }
    
    /**
     * Throttle login attempts
     */
    protected function throttle(Request $request)
    {
        $key = $this->throttleKey($request);
        $maxAttempts = 5; // Maksimal 5 percobaan
        $decayMinutes = 15; // Blokir selama 15 menit
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'email' => [
                    'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . 
                    gmdate('i:s', $seconds) . ' menit.'
                ]
            ]);
        }
    }
    
    /**
     * Increment login attempts
     */
    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit($this->throttleKey($request), 15 * 60); // 15 menit
    }
    
    /**
     * Clear login attempts
     */
    protected function clearLoginAttempts(Request $request)
    {
        RateLimiter::clear($this->throttleKey($request));
    }
    
    /**
     * Get throttle key
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }


}
