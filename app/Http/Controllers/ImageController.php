<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Hiển thị danh sách tất cả ảnh.
     */
    public function index()
    {
        $images = Image::with('watch')->get();
        return response()->json($images);
    }

    /**
     * Upload và lưu ảnh vào database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'watch_id' => 'required|exists:watches,id',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/watches', 'public');

            $image = Image::create([
                'watch_id' => $request->watch_id,
                'path_image' => $path
            ]);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'image' => $image
            ], 201);
        }

        return response()->json(['message' => 'Image upload failed'], 400);
    }

    /**
     * Hiển thị thông tin của một ảnh cụ thể.
     */
    public function show($id)
    {
        $image = Image::with('watch')->find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->json($image);
    }

    /**
     * Cập nhật ảnh (upload ảnh mới).
     */
    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            Storage::disk('public')->delete($image->path_image);

            // Lưu ảnh mới
            $path = $request->file('image')->store('uploads/watches', 'public');
            $image->update(['path_image' => $path]);

            return response()->json([
                'message' => 'Image updated successfully',
                'image' => $image
            ]);
        }

        return response()->json(['message' => 'Image update failed'], 400);
    }

    /**
     * Xóa ảnh.
     */
    public function destroy($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Xóa ảnh khỏi storage
        Storage::disk('public')->delete($image->path_image);

        // Xóa ảnh khỏi database
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}
