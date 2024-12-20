<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle user login request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input untuk memastikan format email dan password diisi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            // Kembali ke halaman login dengan error jika validasi gagal
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil kredensial yang diinputkan user (email dan password)
        $credentials = $request->only('email', 'password');

        // Coba autentikasi user dengan kredensial
        if (Auth::attempt($credentials)) {
            // Jika berhasil, arahkan user ke halaman dashboard
            return redirect()->intended($this->redirectTo);
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return redirect()->back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Handle user logout and redirect.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        // Redirect ke halaman login dengan pesan logout sukses
        return redirect('/login')->with('status', 'Logout berhasil!');
    }
}
