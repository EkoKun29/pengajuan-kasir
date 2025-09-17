<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    protected $session;
    protected $timeout = 600; // 10 menit dalam detik

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // Abaikan pemeriksaan untuk path login dan logout
        $isLoggedIn = !in_array($request->path(), ['login', 'logout']);

        if (!session('last_activity') && $isLoggedIn) {
            $this->session->put('last_activity', time());
        }

        if ($isLoggedIn && session('last_activity') && (time() - $this->session->get('last_activity')) > $this->timeout) {
            Auth::logout();
            $this->session->forget('last_activity');
            $this->session->flash('timeout', 'Anda telah logout otomatis karena tidak ada aktivitas selama 10 menit.');
            return redirect()->route('login');
        }

        $this->session->put('last_activity', time());
        
        return $next($request);
    }
}