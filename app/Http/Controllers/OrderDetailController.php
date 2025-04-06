<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Watch;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các chi tiết đơn hàng.
     */
    public function index()
    {
        $orderDetails = OrderDetail::with(['order', 'watch'])->paginate();
        return response()->json($orderDetails);
    }

    /**
     * Lưu một chi tiết đơn hàng mới vào database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'watches_id' => 'required|exists:watches,id',
            'quantity' => 'required|numeric|min:1',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $price = $request->price;
        if (!$price) {
            $watch = Watch::find($request->watches_id);
            if (!$watch) {
                return response()->json(['message' => 'Watch not found'], 404);
            }
            $price = $watch->price;
        }

        $orderDetail = OrderDetail::create([
            'order_id' => $request->order_id,
            'watches_id' => $request->watches_id,
            'quantity' => $request->quantity,
            'price' => $price,
            'discount' => $request->discount ?? 0,
        ]);

        return response()->json([
            'message' => 'Order detail created successfully',
            'order_detail' => $orderDetail
        ], 201);
    }


    /**
     * Hiển thị thông tin của một chi tiết đơn hàng cụ thể.
     */
    public function show($id)
    {
        $orderDetail = OrderDetail::with(['order', 'watch'])->find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        return response()->json($orderDetail);
    }

    /**
     * Cập nhật thông tin của một chi tiết đơn hàng.
     */
    public function update(Request $request, $id)
    {
        $orderDetail = OrderDetail::find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        $request->validate([
            'quantity' => 'sometimes|numeric|min:1',
            'price' => 'sometimes|numeric|min:0',
            'discount' => 'sometimes|numeric|min:0',
        ]);

        $data = $request->all();
        if ($request->has('quantity') || $request->has('price') || $request->has('discount')) {
            $quantity = $request->quantity ?? $orderDetail->quantity;
            $price = $request->price ?? $orderDetail->price;
            $discount = $request->discount ?? $orderDetail->discount;
            $data['total_price'] = ($price * $quantity) - $discount;
        }

        $orderDetail->update($data);

        return response()->json([
            'message' => 'Order detail updated successfully',
            'order_detail' => $orderDetail
        ]);
    }


    /**
     * Xóa một chi tiết đơn hàng khỏi database.
     */
    public function destroy($id)
    {
        $orderDetail = OrderDetail::with('order')->find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        if ($orderDetail->order->status === 'completed') {
            return response()->json(['message' => 'Cannot delete order detail from a completed order'], 400);
        }

        $orderDetail->delete();

        return response()->json(['message' => 'Order detail deleted successfully']);
    }


    //API lấy chi tiết đơn hàng theo order_id
    public function getByOrder($orderId)
    {
        $orderDetails = OrderDetail::where('order_id', $orderId)->get();

        return response()->json([
            "message" => "Danh sách chi tiết đơn hàng",
            "data" => $orderDetails
        ]);
    }
}
