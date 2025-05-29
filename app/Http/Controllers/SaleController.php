<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class SaleController extends Controller
{
    public function sale()
    {
        $products = Product::latest()->paginate(20);
        return view('products.sale', compact('products'));
    }

    public function index(Request $request)
    {
        $ds = isset($request->sname)
            ? Sale::where("name", "like", "%" . trim($request->sname) . "%")->get()
            : Sale::all();

        return view("sales.index", ["list" => $ds]);
    }

    public function delete($id)
    {
        $Sale = Sale::findOrFail($id);
        $Sale->delete();

        return redirect('/sales')->with('success', 'Product deleted successfully!');
    }

    public function detail($id)
    {
        $sp = Sale::find($id);
        return view("sales.detail", ["item" => $sp]);
    }

    public function edit($id)
    {
        $sp = Sale::find($id);
        return view("sales.edit", ["item" => $sp]);
    }

    public function create()
    {
        return view("sales.create")->with('success', 'Product created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:5000000',
            'discount_percent' => 'required|integer|min:1|max:100',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $sale = Sale::findOrFail($id);
        $sale->name = $request->name;
        $sale->brand = $request->brand;
        $sale->price = $request->price;
        $sale->discount_percent = $request->discount_percent;

        if ($request->hasFile('picture')) {
            $imageName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('images'), $imageName);
            $sale->picture = $imageName;
        }

        $sale->save();

        return redirect('/sales')->with('success', 'Product updated successfully!');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:5000000',
            'picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->all();
        $imageName = '';

        if ($request->hasFile('picture')) {
            $imageName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('images'), $imageName);
        }

        $sale = new Sale();
        $sale->name = $data['name'];
        $sale->brand = $data['brand'];
        $sale->price = $data['price'];
        $sale->picture = $imageName;
        $sale->save();

        return redirect()->route('sales.create')->with('success', 'Product created successfully!');
    }
}
