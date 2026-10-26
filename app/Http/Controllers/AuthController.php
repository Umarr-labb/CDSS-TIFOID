<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginSelector()
    {
        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function showDokterLoginForm()
    {
        return view('auth.dokter-login');
    }

    public function showPerawatLoginForm()
    {
        return view('auth.perawat-login');
    }

    public function loginAdmin(Request $request)
    {
        return $this->login($request, 'admin');
    }

    public function loginDokter(Request $request)
    {
        return $this->login($request, 'dokter');
    }

    public function loginPerawat(Request $request)
    {
        return $this->login($request, 'perawat');
    }

    protected function login(Request $request, string $role)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials['role'] = $role;

        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password'], 'role' => $role])) {
            $request->session()->regenerate();

            return redirect()->to($this->redirectToRole($role));
        }

        return back()->withErrors([
            'name' => 'Nama atau password salah untuk role ' . ucfirst($role) . '.',
        ])->onlyInput('name');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirectToRole(string $role): string
    {
        return route('dashboard');
    }
}
