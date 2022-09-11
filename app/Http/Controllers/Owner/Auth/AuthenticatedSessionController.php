<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Log::debug('owner',['AuthenticatedSessionController.create']);
        return view('owner.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->authenticate();

        //session再発行
        $request->session()->regenerate();
        Log::debug('owner_store', $request->session()->all());

        return redirect()->intended(RouteServiceProvider::OWNER_HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('owners')->logout();

        //session無効化
        $request->session()->invalidate();

        //token再生成
        $request->session()->regenerateToken();

        return redirect('/owner/login');
    }
}
