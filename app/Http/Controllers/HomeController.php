<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use App\Http\Requests\ProductRequest; // Giả sử bạn có một ProductRequest để validate dữ liệu

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get(); // hoặc Product::all();

        return view('home', compact('products')); // 
    }
}
