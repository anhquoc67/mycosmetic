<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        // Thêm điều kiện is_active = 1
        $credentials['is_active'] = true;

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }

        // Trường hợp bị tạm ngưng
        $user = User::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị tạm ngưng hoặc bị xoá.']);
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu sai']);
    }

    

    public function logout(Request $request)
    {
        Auth::logout();                         // Huỷ phiên đăng nhập
        $request->session()->invalidate();      // Xoá session cũ
        $request->session()->regenerateToken(); // Bảo vệ CSRF mới

        return redirect()->route('login')->with('message', 'Bạn đã đăng xuất thành công.');
    }
}
