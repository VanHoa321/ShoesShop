@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}" class="text-info">Người dùng</a></li>
                            <li class="breadcrumb-item active">Danh sách khách hàng</li>
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
                                    <a type="button" class="btn btn-success" href="{{route('customer.create')}}">
                                        <i class="fa-solid fa-plus" title="Thêm mới khách hàng"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Hình ảnh</th>
                                            <th>Họ tên</th>
                                            <th>Số điện thoại</th>
                                            <th>Email</th>
                                            <th>Ngày tạo</th>
                                            <th>Phân quyền</th>
                                            <th>Trạng thái</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($items as $item)
                                            <tr id="customer-{{ $item->id }}">
                                                <td>{{ $counter++ }}</td>
                                                <td><img class="img-fluid img-circle" src="{{ $item->avatar ? $item->avatar : "/storage/files/1/Avatar/12225935.png" }}" alt="" style="width: 60px; height: 60px"></td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->phone ? $item->phone : "Chưa cập nhật" }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                <td>{{ $item->role->name }}</td>
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

            $('body').on('click', '.btn-lock-acc, .btn-unlock-acc', function (e) {
                e.preventDefault();
                var button = $(this);
                const id = button.data('id');
                var statusColumn = button.closest('tr').find('.status-column');
                var actionTitle = button.hasClass('btn-lock-acc') ? "Xác nhận khóa tài khoản?" : "Xác nhận mở khóa tài khoản?";
                var actionConfirmText = button.hasClass('btn-lock-acc') ? "Khóa" : "Mở khóa";
                
                Swal.fire({
                    title: actionTitle,
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: actionConfirmText,
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/customer/change/" + id,
                            type: "POST",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    if (response.status) {
                                        button
                                            .removeClass('btn-success btn-unlock-acc')
                                            .addClass('btn-warning text-white btn-lock-acc')
                                            .attr('title', 'Khóa tài khoản')
                                            .html('<i class="fa-solid fa-lock"></i>');
                                        statusColumn.html('<span class="btn btn-sm btn-success" style="width:100px">Hoạt động</span>');
                                    } else {
                                        button
                                            .removeClass('btn-warning btn-lock-acc')
                                            .addClass('btn-success btn-unlock-acc')
                                            .attr('title', 'Mở khóa tài khoản')
                                            .html('<i class="fa-solid fa-lock-open"></i>');
                                        statusColumn.html('<span class="btn btn-sm text-white btn-warning" style="width:100px">Bị khóa</span>');
                                    }
                                } else {
                                    toastr.error('Có lỗi khi thay đổi trạng thái tài khoản');
                                }
                            },
                            error: function (xhr, status, error) {
                                toastr.error('Có lỗi khi thực hiện yêu cầu: ' + xhr.responseText);
                            }
                        });
                    }
                });
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3000);
        })
    </script>
@endsection