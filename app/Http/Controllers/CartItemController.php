<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Watch;
use App\Models\Size;

class CartItemController extends Controller
{
    /**
     * Lấy danh sách tất cả mục trong giỏ hàng.
     */
    public function index()
    {
        $cartItems = CartItem::with(['watch', 'size'])->get();
        return response()->json($cartItems);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'watch_id' => 'required|exists:watches,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $cartItem = CartItem::create([
            'cart_id' => $request->cart_id,
            'watch_id' => $request->watch_id,
            'size_id' => $request->size_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
            'data' => $cartItem
        ], 201);
    }

    /**
     * Lấy thông tin chi tiết một mục trong giỏ hàng.
     */
    public function show($id)
    {
        $cartItem = CartItem::with(['watch', 'size'])->find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Mục trong giỏ hàng không tồn tại'], 404);
        }

        return response()->json($cartItem);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     */
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Mục trong giỏ hàng không tồn tại'], 404);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'message' => 'Cập nhật số lượng thành công!',
            'data' => $cartItem
        ]);
    }

    /**
     * Xóa một mục khỏi giỏ hàng.
     */
    public function destroy($id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Mục trong giỏ hàng không tồn tại'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!']);
    }
}
