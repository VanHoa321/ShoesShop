@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('product.index') }}" class="text-info">Quản lý sản phẩm</a></li>
                            <li class="breadcrumb-item active">Danh sách sản phẩm</li>
                        </ol>
                    </div>
                </div>
            </div>
            @if(Session::has('messenge') && is_array(Session::get('messenge')))
                @php
                    $messenge = Session::get('messenge');
                @endphp
                @if(isset($messenge['style']) && isset($messenge['msg']))
                    <div class="alert alert-{{ $messenge['style'] }}" role="alert" style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                        <i class="bi bi-check2 text-{{ $messenge['style'] }}"></i>{{ $messenge['msg'] }}
                    </div>
                    @php
                        Session::forget('messenge');
                    @endphp
                @endif
            @endif
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <a type="button" class="btn btn-success" href="{{route('product.create')}}">
                                        <i class="fa-solid fa-plus" title="Thêm mới sản phẩm"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Thương hiệu</th>
                                            <th>Giá bán</th>
                                            <th>Mới</th>
                                            <th>Giảm giá</th>
                                            <th>Nổi bật</th>
                                            <th>Trạng thái</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($items as $item)
                                            <tr id="product-{{ $item->id }}">
                                                <td>{{ $counter++ }}</td>
                                                <td><img class="img-fluid img-circle" src="{{ $item->thumbnail }}" style="width: 80px; height: 80px"></td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->code}}</td>
                                                <td>{{ $item->brand->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                <td class="status-column">
                                                    @switch($item->status)
                                                        @case(1)
                                                            <span class="btn btn-sm btn-success" style="width:100px">Hoạt động</span>
                                                            @break
                                                        @case(0)
                                                            <span class="btn btn-sm text-white btn-warning" style="width:100px">Bị khóa</span>
                                                            @break
                                                        @default
                                                            Không xác định
                                                    @endswitch
                                                </td>    
                                                <td>
                                                    <a href="{{ route("customer.edit", $item->id) }}" class="btn btn-info btn-sm" title="Sửa thông tin tài khoản">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    @if ($item->status)
                                                        <a class="btn btn-warning btn-sm text-white btn-lock-acc" data-id="{{ $item->id }}" title="Khóa tài khoản người dùng">
                                                            <i class="fa-solid fa-lock"></i>
                                                        </a>
                                                    @else
                                                        <a class="btn btn-success btn-sm btn-unlock-acc" data-id="{{ $item->id }}" title="Mở khóa tài khoản">
                                                            <i class="fa-solid fa-lock-open"></i>
                                                        </a>
                                                    @endif
                                                    <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" title="Xóa khách hàng">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $('body').on('change', '.IsActive', function(e) {
                e.preventDefault();
                var check = $(this);
                const id = check.val();
                $.ajax({
                    url: "/admin/customer/change/" + id,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi đổi trạng thái');
                    }
                });
            })

            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa khách hàng?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/customer/destroy/" + id,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                     $('#customer-' + id).remove();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON.message || 'Đã có lỗi xảy ra');
                            }
                        });
                    }
                });
            })

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3000);
        })
    </script>
@endsection