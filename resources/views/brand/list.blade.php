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
        <h2 class="mb-4 text-center text-primary">Danh sách Thương Hiệu Đồng Hồ</h2>

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('brands') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm Thương Hiệu</a>
            <input type="text" class="form-control w-25" placeholder="🔍 Tìm kiếm...">
        </div>

        <table class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Thương hiệu</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr class="align-middle text-center">
                    <td>{{ $brand->id }}</td>
                    <td class="fw-bold text-uppercase">{{ $brand->name }}</td>
                    <td>
                        <a href="{{ route('brands.show', $brand->id) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
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