<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "message" => "Danh sách UserAddress",
            "data" => UserAddress::with('user')->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'label' => 'required|string',
            'receive_name' => 'required|string',
            'receive_phone' => 'required|string|max:10',
            'is_default' => 'required|boolean'
        ]);

        // Nếu địa chỉ được đặt làm mặc định, đặt tất cả địa chỉ khác thành `false`
        // if ($request->is_default) {
        //     UserAddress::where('user_id', $request->user_id)->update(['is_default' => false]);
        // }

        if ($request->is_default) {
            UserAddress::where('user_id', $request->user_id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }


        $useraddress = UserAddress::create([
            'user_id' => $request->user_id,
            'label' => $request->label,
            'receive_name' => $request->receive_name,
            'receive_phone' => $request->receive_phone,
            'is_default' => $request->is_default,
        ]);

        return response()->json([
            "message" => "Đã thêm thành công!",
            "data" => $useraddress
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $useraddress = UserAddress::with('user')->find($id);

        if (!$useraddress) {
            return response()->json(['message' => 'Dữ liệu khhông tồn tại'], 404);
        }

        return response()->json($useraddress);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $useraddress = UserAddress::find($id);

    //     if (!$useraddress) {
    //         return response()->json(['message' => 'Dữ liệu khhông tồn tại'], 404);
    //     }

    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'label' => 'required|string',
    //         'receive_name' => 'required|string',
    //         'receive_phone' => 'required|string|max:10',
    //         'is_default' => 'required|boolean'
    //     ]);

    //     // if ($request->is_default) {
    //     //     UserAddress::where('user_id', $request->user_id)->update(['is_default' => false]);
    //     // }

    //     if ($request->is_default) {
    //         UserAddress::where('user_id', $request->user_id)
    //             ->where('is_default', true)
    //             ->update(['is_default' => false]);
    //     }


    //     $useraddress->update([
    //         'user_id' => $request->user_id,
    //         'label' => $request->label,
    //         'receive_name' => $request->receive_name,
    //         'receive_phone' => $request->receive_phone,
    //         'is_default' => $request->is_default,
    //     ]);

    //     return response()->json([
    //         'message' => 'Đã cập nhật thành công',
    //         'data' => $useraddress
    //     ]);
    // }

    public function update(Request $request, $id)
    {
        $userAddress = UserAddress::find($id);

        if (!$userAddress) {
            return response()->json(['message' => 'Dữ liệu không tồn tại'], 404);
        }

        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'label' => 'sometimes|string',
            'receive_name' => 'sometimes|string',
            'receive_phone' => 'sometimes|string|max:10',
            'is_default' => 'sometimes|boolean'
        ]);

        if ($request->is_default) {
            UserAddress::where('user_id', $userAddress->user_id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $userAddress->update(array_filter($request->only([
            'user_id',
            'label',
            'receive_name',
            'receive_phone',
            'is_default'
        ])));

        return response()->json([
            'message' => 'Đã cập nhật thành công',
            'data' => $userAddress
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userAddress = UserAddress::find($id);

        if (!$userAddress) {
            return response()->json(['message' => 'Dữ liệu không tồn tại'], 404);
        }

        $userId = $userAddress->user_id;
        $wasDefault = $userAddress->is_default;

        $userAddress->delete();

        // Nếu địa chỉ bị xóa là mặc định, đặt một địa chỉ khác làm mặc định
        if ($wasDefault) {
            $newDefault = UserAddress::where('user_id', $userId)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return response()->json([
            'message' => "Đã xóa thành công!"
        ]);
    }


    //API lấy danh sách địa chỉ của 1 user
    public function listByUser($userId)
    {
        $addresses = UserAddress::where('user_id', $userId)->paginate(10);

        return response()->json([
            "message" => "Danh sách địa chỉ của user",
            "data" => $addresses
        ]);
    }

    //API đặt mặc định
    public function setDefault($id)
    {
        $address = UserAddress::find($id);

        if (!$address) {
            return response()->json(['message' => 'Dữ liệu không tồn tại'], 404);
        }

        UserAddress::where('user_id', $address->user_id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);

        return response()->json([
            'message' => 'Địa chỉ mặc định đã được cập nhật!',
            'data' => $address
        ]);
    }
}