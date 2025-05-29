<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hot; // Assuming you have a Hot model

class HotController extends Controller
{
    public function index(Request $request)
    {
        $ds = isset($request->sname)
            ? Hot::where("name", "like", "%" . trim($request->sname) . "%")->get()
            : Hot::all();

        return view("hots.index", ["list" => $ds]);
    }

    public function delete($id)
    {
        $Hot = Hot::findOrFail($id);
        $Hot->delete();

        return redirect('/hots')->with('success', 'Product deleted successfully!');
    }

    public function detail($id)
    {
        $sp = Hot::find($id);
        return view("hots.detail", ["item" => $sp]);
    }

    public function edit($id)
    {
        $sp = Hot::find($id);
        return view("hots.edit", ["item" => $sp]);
    }

    public function create()
    {
        return view("hots.create");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:5000000',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $hot = Hot::findOrFail($id);
        $hot->name = $request->name;
        $hot->brand = $request->brand;
        $hot->price = $request->price;

        if ($request->hasFile('picture')) {
            $imageName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('images'), $imageName);
            $hot->picture = $imageName;
        }

        $hot->save();

        return redirect('/hots')->with('success', 'Product updated successfully!');
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

        $hot = new Hot();
        $hot->name = $data['name'];
        $hot->brand = $data['brand'];
        $hot->price = $data['price'];
        $hot->picture = $imageName;
        $hot->save();

        return redirect()->route('hots.create')->with('success', 'Product created successfully!');
    }
}
