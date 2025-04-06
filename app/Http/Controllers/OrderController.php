<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Danh Sách',
            'data' => Order::with('user', 'orderDetails', 'transaction')->paginate()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
            'total' => 'required|numeric|min:0',
            'order_date' => 'required|date|after_or_equal:today',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'total' => $request->total,
            'order_date' => $request->order_date,
        ]);

        return response()->json([
            'message' => 'Đã thêm thành công!',
            'data' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('user', 'orderDetails', 'transaction')->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Dữ liệu không tồn tại!'
            ], 404);
        }

        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Dữ liệu không tồn tại!'
            ], 404);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
            'total' => 'required|numeric|min:0',
            'order_date' => 'required|date|after_or_equal:today',
        ]);

        $order->update([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'total' => $request->total,
            'order_date' => $request->order_date,
        ]);

        return response()->json([
            'message' => "Đã cập nhật thành công!",
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::with('orderDetails', 'transaction')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Dữ liệu không tồn tại!'], 404);
        }

        if ($order->orderDetails->count() > 0 || $order->transaction) {
            return response()->json(['message' => 'Không thể xóa đơn hàng có giao dịch hoặc sản phẩm!'], 400);
        }

        $order->delete();

        return response()->json(['message' => "Đã xóa thành công!"]);
    }

    //API lấy danh sách đơn hàng theo user_id
    public function getByUser($userId)
    {
        $orders = Order::where('user_id', $userId)->paginate(10);

        return response()->json([
            "message" => "Danh sách đơn hàng của người dùng",
            "data" => $orders
        ]);
    }

    //Kiểm tra trước khi xóa đơn hàng (destroy())

}