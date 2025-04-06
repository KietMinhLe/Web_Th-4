<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return response()->json(Transaction::with('order')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id|unique:transactions,order_id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed',
        ]);

        $transaction = Transaction::create($request->only([
            'order_id',
            'payment_method',
            'amount',
            'status'
        ]));

        return response()->json([
            'message' => 'Đã thêm thành công',
            'transaction' => $transaction
        ], 201);
    }


    public function show($id)
    {
        $transaction = Transaction::with('order')->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Dữ liệu không tồn tại!!!'], 404);
        }

        return response()->json($transaction);
    }


    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Dữ liệu không tồn tại!!!'], 404);
        }

        $request->validate([
            'payment_method' => 'sometimes|string',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,completed,failed',
        ]);

        $transaction->update(array_filter($request->only([
            'payment_method',
            'amount',
            'status'
        ])));

        return response()->json([
            'message' => 'Cập nhật thành công',
            'transaction' => $transaction
        ]);
    }


    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Dữ liệu không tồn tại!!!'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Đã xóa thành công']);
    }

    //API lấy danh sách giao dịch theo order_id
    public function listByOrder($orderId)
    {
        $transactions = Transaction::where('order_id', $orderId)->paginate(10);

        return response()->json([
            "message" => "Danh sách giao dịch của order",
            "data" => $transactions
        ]);
    }

}