<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Đồng Hồ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center text-primary">Danh sách Đồng Hồ</h2>

        <div class="d-flex justify-content-between mb-3">
            <a href="{{route('watches')}}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm Đồng Hồ</a>
            <input type="text" class="form-control w-25" placeholder="🔍 Tìm kiếm...">
        </div>

        <table class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Tên đồng hồ</th>
                    <th>Thương hiệu</th>
                    <th>Giá (VNĐ)</th>
                    <th>Số lượng</th>
                    <th>Mô tả</th>
                    <th>Ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($watches as $watch)
                <tr class="align-middle text-center">
                    <td>{{ $watch->id }}</td>
                    <td class="fw-bold text-uppercase">{{ $watch->name }}</td>
                    <td class="text-muted">{{ $watch->brand }}</td>
                    <td class="text-success fw-bold">{{ number_format($watch->price, 0, ',', '.') }} đ</td>
                    <td class="text-primary">{{ $watch->stock }}</td>
                    <td class="text-wrap" style="max-width: 200px;">{{ $watch->description }}</td>
                    <td>
                        @if($watch->image)
                        <img src="{{ asset('storage/' . $watch->image) }}" alt="Ảnh đồng hồ"
                            class="img-thumbnail shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                        <span class="text-danger">Không có ảnh</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('watches.show', $watch->id) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <a href="{{ route('watches.edit', $watch->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('watches.destroy', $watch->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa không?')">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>