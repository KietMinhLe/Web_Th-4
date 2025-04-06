<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật Đồng Hồ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Cập nhật Đồng Hồ</h2>
        <form action="{{ route('watches.update', $watch->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Tên đồng hồ</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $watch->name) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="brand_id" class="form-label">Thương hiệu</label>
                <select class="form-control" id="brand_id" name="brand" required>
                    <option value="">Chọn thương hiệu</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand', $watch->brand) == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" class="form-control" id="price" name="price"
                    value="{{ old('price', $watch->price) }}" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Số lượng</label>
                <input type="number" class="form-control" id="stock" name="stock"
                    value="{{ old('stock', $watch->stock) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description"
                    rows="3">{{ old('description', $watch->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ảnh đồng hồ mới</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            @if($watch->image)
            <div class="mb-3">
                <label class="form-label">Ảnh hiện tại</label><br>
                <img src="{{ asset('storage/' . $watch->image) }}" alt="Ảnh đồng hồ" style="max-width:200px;">
            </div>
            @endif

            <button type="submit" class="btn btn-primary">Cập nhật đồng hồ</button>
        </form>
    </div>
</body>

</html>