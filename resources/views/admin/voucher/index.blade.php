@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route("voucher.index") }}" class="text-info">Voucher</a></li>
                            <li class="breadcrumb-item active text-secondary">Danh sách Voucher</li>
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
                                <a type="button" class="btn btn-success" href="{{ route('voucher.create') }}">
                                    <i class="fa-solid fa-plus" title="Thêm mới Voucher"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mã Voucher</th>
                                        <th>Loại áp dụng</th>
                                        <th>Giá trị giảm</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Trạng thái</th>
                                        <th>Chức năng</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                        $counter = 1; 
                                    @endphp
                                    @foreach ($items as $item)
                                        <tr id="voucher-{{ $item->id }}">
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>
                                                @if ($item->type == 1)
                                                    <span class="badge-primary p-1" style="font-size: 15px; width: 150px; display: inline-block; text-align: center;">Giảm theo giá tiền</span>                                          
                                                @elseif ($item->type == 2)
                                                    <span class="badge-info p-1" style="font-size: 15px; width: 150px; display: inline-block; text-align: center;">Giảm theo phần trăm</span>  
                                                @endif
                                            </td>
                                            <td>
                                                {{ number_format($item->discount_value, 0, ',', '.') }}
                                                {{ $item->type == 1 ? 'đ' : '%' }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input IsActive" id="customSwitch{{ $item->id }}" {{ $item->status ? 'checked' : '' }} value="{{ $item->id }}">
                                                    <label class="custom-control-label" for="customSwitch{{ $item->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('voucher.edit', $item->id) }}"
                                                   class="btn btn-info btn-sm" title="Sửa Voucher">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                   data-id="{{ $item->id }}" title="Xóa Voucher">
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
        $(document).ready(function () {

            $('body').on('change', '.IsActive', function (e) {
                e.preventDefault();
                const id = $(this).val();
                $.ajax({
                    url: "/admin/voucher/change/" + id,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function () {
                        toastr.error('Có lỗi xảy ra khi đổi trạng thái');
                    }
                });
            });

            $('body').on('click', '.btn-delete', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa Voucher?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/voucher/destroy/" + id,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                toastr.success(response.message);
                                $('#voucher-' + id).remove();
                            },
                            error: function () {
                                toastr.error('Có lỗi khi xóa Voucher');
                            }
                        });
                    }
                });
            });

            setTimeout(function () {
                $("#myAlert").fadeOut(500);
            }, 3500);
        });
    </script>
@endsection
