<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Size;

class SizeController extends Controller
{
    /**
     * Lấy danh sách tất cả kích thước.
     */
    public function index()
    {
        $sizes = Size::all();
        return response()->json($sizes);
    }

    /**
     * Thêm mới một kích thước.
     */
    public function store(Request $request)
    {
        $request->validate([
            'watch_id' => 'required|exists:watches,id',
            'size_name' => 'required|string|max:10|unique:sizes,size_name,NULL,id,watch_id,' . $request->watch_id,
        ]);

        $size = Size::create([
            'watch_id' => $request->watch_id,
            'size_name' => $request->size_name,
        ]);

        return response()->json([
            'message' => 'Thêm kích thước thành công!',
            'data' => $size
        ], 201);
    }



    /**
     * Lấy chi tiết một kích thước.
     */
    public function show($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Kích thước không tồn tại'], 404);
        }

        return response()->json($size);
    }

    /**
     * Cập nhật kích thước.
     */
    public function update(Request $request, $id)
    {
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Kích thước không tồn tại'], 404);
        }

        $request->validate([
            'watch_id' => 'required|exists:watches,id',
            'size_name' => 'required|string|max:10|unique:sizes,size_name,' . $id . ',id,watch_id,' . $request->watch_id,
        ]);

        $size->update([
            'watch_id' => $request->watch_id,
            'size_name' => $request->size_name,
        ]);

        return response()->json([
            'message' => 'Cập nhật kích thước thành công!',
            'data' => $size
        ]);
    }



    /**
     * Xóa kích thước.
     */
    public function destroy($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Kích thước không tồn tại'], 404);
        }

        $size->delete();

        return response()->json(['message' => 'Xóa kích thước thành công!']);
    }

    //API lấy danh sách kích thước theo đồng hồ (watch_id)
    public function getByWatch($watchId)
    {
        $sizes = Size::where('watch_id', $watchId)->paginate(10);

        return response()->json([
            "message" => "Danh sách kích thước của đồng hồ",
            "data" => $sizes
        ]);
    }
}
