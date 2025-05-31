<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CosmeticController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::withTrashed(); // include cả deleted

        if ($request->has('sname')) {
            $query->where('name', 'like', '%' . trim($request->sname) . '%');
        }

        $ds = $query->paginate(10);

        return view('admin.products.index', ['list' => $ds]);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        // Kiểm tra quyền admin ở đây nếu cần
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền xóa.');
        }

        // Gắn is_active = false
        $product->is_active = false;
        $product->save();
        $product->delete();
        // Ghi log
        Log::channel('customlog')->info("Admin [ID: " . auth()->id() . "] đã xóa sản phẩm: {$product->name} (ID: {$product->id}) lúc " . now());

        return redirect()->back()->with('message', 'Sản phẩm đã được ẩn.');
    }

    public function detail($id)
    {
        $sp = Product::find($id);
        return view("admin.products.detail", ["item" => $sp]);
    }

    public function edit($id)
    {
        $sp = Product::find($id);
        return view("admin.products.edit", ["item" => $sp]);
    }

    public function create()
    {
        return view("admin.products.create");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:5000000',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->price = $request->price;
        $product->discount_percent = $request->discount_percent ?? 0;

        if ($request->hasFile('picture')) {
            $imageName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('image/products'), $imageName); // Thống nhất đường dẫn
            $product->image = $imageName;
        }

        $product->save();

        Log::channel('customlog')->info("Admin [ID: " . auth()->id() . "] đã cập nhật sản phẩm: {$product->name} (ID: {$product->id}) lúc " . now());
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:5000000',
            'discount_percent' => 'nullable|integer|min:1|max:100',
            'picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->all();
        $imageName = '';

        if ($request->hasFile('picture')) {
            $imageName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('image/products'), $imageName);
        }

        $product = new Product();
        $product->name = $data['name'];
        $product->brand = $data['brand'];
        $product->price = $data['price'];
        $product->discount_percent = $data['discount_percent'] ?? 0; 
        $product->image = $imageName;
        $product->save();

        return redirect()->route('admin.products.create')->with('success', 'Product created successfully!');
    }


    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền phục hồi.');
        }
        if ($product->trashed()) {
            $product->restore(); // khôi phục deleted_at
            $product->is_active = true; // bật lại trạng thái hoạt động
            $product->save();
        }
        Log::channel('customlog')->info("Admin [ID: " . auth()->id() . "] đã phục hồi sản phẩm: {$product->name} (ID: {$product->id}) lúc " . now());
        
        return redirect()->back()->with('message', 'Sản phẩm đã được khôi phục.');
    }
 
}
