<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Admin::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'admin_name' => 'required|string|unique:admins,admin_name|max:255',
            'password'   => 'required|string|min:6',
            'email'      => 'required|email|unique:admins,email',
            'phone'      => 'required|string|max:10'
        ]);

        // Tạo admin mới và mã hóa mật khẩu
        $admin = Admin::create([
            'admin_name' => $request->admin_name,
            'password'   => bcrypt($request->password), // Mã hóa mật khẩu
            'email'      => $request->email,
            'phone'      => $request->phone
        ]);

        return response()->json([
            'message' => 'Thêm admin thành công!',
            'data'    => $admin
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Admin không tồn tại'], 404);
        }

        return response()->json($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Admin không tồn tại'], 404);
        }

        $request->validate([
            'admin_name' => 'required|string|unique:admins,admin_name,' . $id . '|max:255',
            'password'   => 'required|string|min:6',
            'email'      => 'required|email|unique:admins,email' . $id,
            'phone'      => 'required|string|min:10',
        ]);

        $admin->update([
            'admin_name' => $request->admin_name,
            'password' => $request->password,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Chỉ mã hóa nếu có mật khẩu mới
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        return response()->json([
            'message' => 'Cập nhật thương hiệu thành công!',
            'data' => $admin
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Admin không tồn tại'], 404);
        }

        $admin->delete();

        return response()->json(['message' => 'Xóa Admin thành công!']);
    }


    //API tìm kiếm Admin theo tên
    public function search(Request $request)
    {
        $query = Admin::query();

        if ($request->has('admin_name')) {
            $query->where('admin_name', 'like', '%' . $request->admin_name . '%');
        }

        return response()->json($query->paginate(10));
    }
}
