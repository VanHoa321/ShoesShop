@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#" class="text-info">Quản lý đơn hàng</a></li>
                            <li class="breadcrumb-item active">Chờ xác nhận</li>
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
                                    <a type="button" class="btn btn-success" href="#">
                                        <i class="fa-solid fa-plus" title=""></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mã đơn hàng</th>
                                            <th>Khách nhận</th>
                                            <th>Địa chỉ giao hàng</th>
                                            <th>Tổng tiền</th>
                                            <th>Ngày đặt</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->customer_name }}<br>{{ $item->customer_phone }}</td>
                                                <td>{{ $item->customer_address }}</td>
                                                <td>{{ number_format($item->total, 0, ',', '.') }} đ</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route("order.details", $item->id) }}" class="btn btn-primary btn-sm" title="Chi tiết đơn hàng">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-success btn-sm btn-pending" data-id="{{ $item->id }}" title="Xác nhận đơn hàng">
                                                        <i class="fa-solid fa-check"></i>
                                                    </a>
                                                    <a href="" class="btn btn-danger btn-sm btn-cancel" data-id="{{ $item->id }}" title="Từ chối đơn hàng">
                                                        <i class="fa-solid fa-ban"></i>
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

            $('body').on('click', '.btn-pending', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận duyệt đơn hàng này?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Duyệt",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/order/update-status/" + id,
                            type: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: 2
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
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

            $('body').on('click', '.btn-cancel', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Nhập lý do hủy đơn hàng",
                    input: "textarea",
                    inputPlaceholder: "VD: Thông tin khách hàng không đúng",
                    inputAttributes: {
                        "aria-label": "Nhập lý do hủy"
                    },
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xác nhận",
                    cancelButtonText: "Hủy",
                    inputValidator: (value) => {
                        if (!value) {
                            return "Vui lòng nhập lý do hủy!";
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        $.ajax({
                            url: "/admin/order/cancel/" + id,
                            type: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: 0,
                                reason: reason
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
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
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3000);
        })
    </script>
@endsection