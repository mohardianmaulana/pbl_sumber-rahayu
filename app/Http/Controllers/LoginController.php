<?php

namespace App\Http\Controllers;

use App\Models\User;
// use Illuminate\Contracts\Session\Session as Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session as FacadesSession;
use Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function actionlogin(Request $request)
    {
        // Validasi Recaptcha
        $recaptcha = $request->input('g-recaptcha-response');
        if (is_null($recaptcha)) {
            Session::flash('error', "Please complete the recaptcha to proceed.");
            return redirect()->back();
        }

        $captchaValid = $this->validateRecaptcha($recaptcha, $request->ip());
        if (!$captchaValid) {


            // Proses Login
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $role = Auth::user()->role->name ?? 'user'; // Asumsi relasi roles
                if ($role === 'owner') {
                    return redirect('dashboard')->with('success', 'Login Berhasil');
                } else if ($role === 'admin') {
                    return redirect('dashboard')->with('success', 'Login Berhasil');
                } else {
                    return redirect('login')->with('success', 'Login Berhasil');
                }
            } else {
                Session::flash('error', 'Email atau Password Salah');
                return redirect('/');
            }
        }
    }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logout Berhasil');
    }

    public function register()
    {
        // Lakukan join antara tabel 'user' dan 'roles' untuk mendapatkan nama role
        $users = User::join('roles', 'users.roles_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as roles_name')
            ->get();

        // Tampilkan view dengan data users
        return view('user.register', compact('users'));
    }

    private function validateRecaptcha($recaptcha, $ip)
    {
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $params = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptcha,
            'remoteip' => $ip,
        ];

        $response = Http::post($url, $params);
        $result = json_decode($response->body());

        return $response->successful() && $result->success;
    }
}
