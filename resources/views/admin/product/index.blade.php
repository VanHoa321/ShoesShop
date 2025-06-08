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
                                                <td style="width:150px">{{ $item->name }}</td>
                                                <td>{{ $item->brand->name }}</td>
                                                <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                                <td>
                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input change-status" data-type="is_new" id="customSwitch1_{{ $item->id }}" {{ $item->is_new ? 'checked' : '' }} value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customSwitch1_{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input change-status" data-type="is_sale" id="customSwitch2_{{ $item->id }}" {{ $item->is_sale ? 'checked' : '' }} value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customSwitch2_{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input change-status" data-type="is_bestseller" id="customSwitch3_{{ $item->id }}" {{ $item->is_bestseller ? 'checked' : '' }} value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customSwitch3_{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input change-status" data-type="status" id="customSwitch4_{{ $item->id }}" {{ $item->status ? 'checked' : '' }} value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customSwitch4_{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route("product.edit", $item->id) }}" class="btn btn-info btn-sm" title="Cập nhật sản phẩm">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" title="Xóa sản phẩm">
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

            $('body').on('change', '.change-status', function(e) {
                e.preventDefault();

                let checkbox = $(this);
                let id = checkbox.val();
                let type = checkbox.data('type');

                $.ajax({
                    url: "/admin/product/change-status/" + id,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: type
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi đổi trạng thái');
                    }
                });
            });

            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa sản phẩm?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/product/destroy/" + id,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                     $('#product-' + id).remove();
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