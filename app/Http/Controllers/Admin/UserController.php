<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Http\Middleware\IsAdmin;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'user')->paginate(10);        //Hiển thị danh sách user
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $user = User::findOrFail($id);

    $province = Province::where('code', $user->province_code)->value('name');
    $district = District::where('code', $user->district_code)->value('name');
    $ward = Ward::where('code', $user->ward_code)->value('name');

    return view('admin.users.show', compact('user', 'province', 'district', 'ward'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã bị xoá.');
    }

    public function orders(User $user)
    {
        $orders = $user->orders;    // Xem đơn hàng của user
        return view('admin.users.orders', compact('user', 'orders'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Trạng thái người dùng đã được cập nhật.');
    }
}
