<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::with(['addresses', 'orders'])->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|unique:users,user_name|max:255',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email'
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'email' => $request->email
        ]);


        return response()->json([
            'message' => "Thêm tên user thành công",
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['addresses', 'orders'])->findOrFail($id);

        if (!$user) {
            return response()->json(['message' => 'Tài khoản không tồn tại'], 404);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Tài khoản không tồn tại'], 404);
        }

        $request->validate([
            'user_name' => 'required|string|unique:users,user_name,' . $id . '|max:255',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email' . $id
        ]);

        $data = $request->only(['user_name', 'phone', 'email']);
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update([
            'user_name' => $request->user_name,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'email' => $request->email
        ]);

        return response()->json([
            "message" => "Đã cập nhật thành công",
            "data" => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Tài khoản không tồn tại'], 404);
        }

        if ($user->orders()->count() > 0) {
            return response()->json([
                "message" => "Không thể xóa user vì có đơn hàng liên quan"
            ], 400);
        }

        $user->delete();

        return response()->json(["message" => "Đã xóa thành công"]);
    }

    //search
    public function search(Request $request)
    {
        $query = User::query();

        if ($request->filled('user_name')) {
            $query->where('user_name', 'like', "%{$request->user_name}%");
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->email}%");
        }

        return response()->json($query->paginate(10));
    }

    //API thay đổi mật khẩu
    // public function changePassword(Request $request, $id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json(['message' => 'Tài khoản không tồn tại'], 404);
    //     }

    //     $request->validate([
    //         'old_password' => 'required|string',
    //         'new_password' => 'required|string|min:6',
    //     ]);

    //     if (!\Hash::check($request->old_password, $user->password)) {
    //         return response()->json(['message' => 'Mật khẩu cũ không đúng'], 400);
    //     }

    //     $user->update(['password' => bcrypt($request->new_password)]);

    //     return response()->json(['message' => 'Đổi mật khẩu thành công']);
    // }


    //API khóa/mở khóa user
    public function updateStatus(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Tài khoản không tồn tại'], 404);
        }

        $request->validate(['status' => 'required|in:0,1']);

        $user->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $user
        ]);
    }
}