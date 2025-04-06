<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Watch;
use App\Models\Size;

class CartController extends Controller
{
    /**
     * Lấy danh sách giỏ hàng.
     */
    public function index()
    {
        $cart = Cart::with('items.watch', 'items.size')->first();

        if (!$cart) {
            return response()->json(['message' => 'Giỏ hàng trống'], 200);
        }

        return response()->json($cart);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function store(Request $request)
    {
        $request->validate([
            'watch_id' => 'required|exists:watches,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate([]); // Tạo giỏ hàng nếu chưa có
        $watch = Watch::find($request->watch_id);

        $cartItem = CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'watch_id' => $request->watch_id, 'size_id' => $request->size_id],
            ['quantity' => $request->quantity, 'price' => $watch->price]
        );

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng!', 'data' => $cartItem], 201);
    }

    /**
     * Hiển thị chi tiết một giỏ hàng.
     */
    public function show($id)
    {
        $cart = Cart::with('items.watch', 'items.size')->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Giỏ hàng không tồn tại'], 404);
        }

        return response()->json($cart);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     */
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cập nhật số lượng thành công!', 'data' => $cartItem]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng.
     */
    public function destroy($id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!']);
    }
}