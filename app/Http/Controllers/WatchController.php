<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Watch;
use Illuminate\Console\View\Components\Warn;

class WatchController extends Controller
{
    public function index()
    {
        $watches = Watch::with('brand')->paginate(10);
        return response()->json($watches);
    }

    //Danh sách đồng hồ
    public function list()
    {
        $watches = Watch::all();

        return view('watch.list', compact('watches'));
    }

    // Form thêm đồng hồ
    public function create()
    {
        $brands = Brand::all();
        return view('watch.watch', compact('brands'));
    }

    // Thêm đồng hồ mới
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|unique:watches,product_name',
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
            'discount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        // Lưu đồng hồ vào database
        $watch = Watch::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'status' => (bool) $request->status,
            'discount' => $request->discount ?? 0.00,
            'quantity' => $request->quantity,
        ]);


        return response()->json([
            'message' => 'Thêm đồng hồ thành công!',
            'data' => $watch
        ], 201);
    }



    // Lấy chi tiết đồng hồ
    public function show($id)
    {
        $watch = Watch::find($id);

        if (!$watch) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        return response()->json($watch);
    }

    // form edit
    public function edit(Watch $watch)
    {
        $brands = Brand::all();

        return view('watch.update_watch', compact('watch', 'brands'));
    }

    //Cập nhật đồng hồ
    public function update(Request $request, $id)
    {
        $watch = Watch::find($id);

        if (!$watch) {
            return response()->json(['message' => 'Đồng hồ không tồn tại'], 404);
        }

        $request->validate([
            'product_name' => 'sometimes|string|unique:watches,product_name,' . $watch->id,
            'description' => 'nullable|string',
            'brand_id' => 'sometimes|required|exists:brands,id',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|boolean',
            'discount' => 'nullable|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
        ]);

        try {
            $watch->update(array_filter($request->only([
                'product_name',
                'description',
                'brand_id',
                'price',
                'status',
                'discount',
                'quantity'
            ]), fn($value) => !is_null($value)));

            return response()->json([
                'message' => 'Cập nhật đồng hồ thành công!',
                'data' => $watch
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Xóa đồng hồ
    public function destroy(Watch $watch)
    {
        $watch->delete();
        return response()->json(["message" => "Đồng hồ đã bị xóa"], 204);
    }


    // Search 
    public function search(Request $request)
    {
        $query = Watch::query();

        if ($request->filled('name')) {
            $query->where('product_name', 'like', "%{$request->name}%");
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        return response()->json($query->paginate(10));
    }

    //cập nhật trạng thái đồng hồ
    public function updateStatus(Request $request, Watch $watch)
    {
        $request->validate(['status' => 'required|in:0,1']);
        $watch->update(['status' => $request->status]);

        return response()->json(['message' => 'Cập nhật trạng thái thành công', 'data' => $watch]);
    }

    //Thống kê tổng số lượng đồng hồ theo thương hiệu
    public function countByBrand()
    {
        $data = Watch::selectRaw('brand_id, COUNT(*) as total')
            ->groupBy('brand_id')
            ->with('brand')
            ->get();

        return response()->json($data);
    }
}