@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#" class="text-info">Quản lý màu sắc</a></li>
                            <li class="breadcrumb-item text-secondary">Danh sách màu sắc</li>
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
                                    <a type="button" class="btn btn-success" href="{{ route('color.create') }}">
                                        <i class="fa-solid fa-plus" title="Thêm mới màu sắc"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên màu</th>
                                            <th>Mã màu</th>
                                            <th>Hiển thị</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($items as $item)
                                            <tr id="color-{{ $item->id }}">
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->code }}</td>
                                                <td>
                                                    <span class="d-inline-block rounded" style="background-color: {{ $item->code ? $item->code : "#FFFFFF" }}; width: 30px; height: 20px; border: 1px solid #ccc;"></span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('color.edit', $item->id) }}" class="btn btn-info btn-sm" title="Cập nhật màu sắc">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" title="Xóa màu sắc">
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
            $('body').on('click', '.btn-delete', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa màu sắc?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/color/destroy/" + id,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#color-' + id).remove();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function () {
                                toastr.error('Có lỗi khi xóa màu sắc');
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
