<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin; // Đảm bảo đã import middleware IsAdmin
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('layouts.admin.dashboard');
    }

    public function settings()
    {
        $products = Product::all();
        return view('admin.settings');  // tạo view này nếu chưa có
    }
}
