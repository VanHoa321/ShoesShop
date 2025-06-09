@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">Quản lý đơn hàng</li>
                        <li class="breadcrumb-item active text-default">Chi tiết đơn hàng</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12 border" id="accordion">
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse1">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Thông tin đơn hàng
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse1" class="collapse show" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Mã đơn hàng</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->code }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Ngày đặt</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Khách đặt</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        @if ($item->user_id)
                                                            {{ $item->user->name }} ({{ $item->user->phone }})
                                                        @else
                                                            Khách lẻ
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Phương thức thanh toán</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Thanh toán qua Momo' }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Ghi chú</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->note ?? "Không có ghi chú đơn hàng" }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Trạng thái đơn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        @if ($item->status == 0)
                                                            <span class="text-danger">Đã hủy</span>
                                                        @elseif ($item->status == 1)
                                                            <span class="text-warning">Chờ xác nhận</span>
                                                        @elseif ($item->status == 2)
                                                            <span class="text-info">Đang chuẩn bị</span>
                                                        @elseif ($item->status == 3)
                                                            <span class="text-primary">Đang giao</span>
                                                        @else
                                                            <span class="text-success">Đã hoàn thành</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse2">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Thành tiền đơn hàng
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse2" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Tiền trước khi giảm giá</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ number_format($item->subtotal, 0, ',', '.') }} đ
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Mã giảm giá áp dụng</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->voucher_code ?? "Không có mã giảm" }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Số tiền giảm</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ number_format($item->discount_amount, 0, ',', '.') }} đ
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Thành tiền đơn hàng</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        <span class="text-danger">{{ number_format($item->total, 0, ',', '.') }} đ</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse3">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Thông tin khách nhận hàng
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse3" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Họ tên</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->customer_name }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Số điện thoại</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->customer_phone }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Địa chỉ nhận hàng</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->customer_address }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Email</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $item->customer_email }}
                                                    </div>
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse4">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Chi tiết đơn hàng
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse4" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="example-table-6" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Sản phẩm</th>
                                                            <th>Kích thước</th>
                                                            <th>Giá bán</th>
                                                            <th>Số lượng</th>
                                                            <th>Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $counter = 1;
                                                        @endphp
                                                        @foreach($item->orderDetails as $detail)
                                                            <tr>
                                                                <td>{{ $counter++ }}</td>                                                                
                                                                <td>
                                                                    <img src="{{ $detail->product->thumbnail }}" class="mr-1 mb-3" style="width: 80px;">
                                                                    {{ $detail->product->name }} / {{ $detail->product->color->name }}
                                                                </td>
                                                                <td>{{ $detail->size }}</td>
                                                                <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                                                <td>{{ $detail->quantity }}</td>
                                                                <td>{{ number_format($detail->total, 0, ',', '.') }} đ</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>                                                          
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="cart-total float-right p-3">                                                       
                                                    <div class="d-flex justify-content-between">
                                                        <span class="mr-1">Tổng tiền:</span>
                                                        <span class="text-danger font-weight-bold"><strong id="cart-total">{{ number_format($item->total, 0, ',', '.') }} đ</strong></span>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route("order.pendingOrders") }}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                            <a href="" class="btn btn-secondary" title="In hóa đơn">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection