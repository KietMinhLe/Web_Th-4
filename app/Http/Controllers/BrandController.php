<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    // Lấy danh sách thương hiệu api
    public function index()
    {
        $brands = Brand::all();
        return response()->json($brands);
    }

    // Danh sách thương hiệu bên 
    public function list()
    {
        $brands = Brand::orderBy('id', 'asc')->get();

        return view('brand.list', compact('brands'));
    }

    // From thêm thương hiệu
    public function create()
    {
        $brands = Brand::all();
        return view('brand.brands', compact('brands'));
    }

    // Thêm mới thương hiệu
    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|unique:brands,brand_name|max:255',
            'description' => 'nullable|string', 
            'status' => 'required|boolean',
        ]);

        $brand = Brand::create([
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Thêm thương hiệu thành công!',
            'data' => $brand
        ], 201);
    }

    // Hiển thị chi tiết một thương hiệu
    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Thương hiệu không tồn tại'], 404);
        }

        return response()->json($brand);
    }

    //Hiện thị form edit
    public function edit(Brand $brand)
    {
        return view('brand.update_brand', compact('brand'));
    }

    // Cập nhật thông tin thương hiệu
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Thương hiệu không tồn tại'], 404);
        }

        $request->validate([
            'brand_name' => 'required|string|unique:brands,brand_name,' . $id . '|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $brand->update([
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Cập nhật thương hiệu thành công!',
            'data' => $brand
        ]);
    }


    // Xóa thương hiệu
    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Thương hiệu không tồn tại'], 404);
        }

        $brand->delete();

        return response()->json(['message' => 'Xóa thương hiệu thành công!']);
    }
}