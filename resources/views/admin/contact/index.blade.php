@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#" class="text-info">Quản lý liên hệ</a></li>
                            <li class="breadcrumb-item text-secondary">Danh sách liên hệ</li>
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
                                    <h3 class="card-title">Danh sách liên hệ</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Người gửi</th>
                                            <th>Email</th>
                                            <th>Nội dung</th>
                                            <th>Ngày gửi</th>
                                            <th>Trạng thái</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $counter = 1;
                                        @endphp
                                        @foreach ($items as $item)
                                        <tr id="contact-{{ $item->id }}">
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $item->user->name ?? 'Khách' }}</td>
                                            <td>{{ $item->user->email ?? 'N/A' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($item->message, 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ !$item->is_read ? 'warning' : ($item->reply_message ? 'info' : 'success') }} px-3 py-2 status-badge" data-id="{{ $item->id }}">
                                                    {{ !$item->is_read ? 'Chưa đọc' : ($item->reply_message ? 'Đã phản hồi' : 'Đã đọc') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('contact.show', $item->id) }}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" title="Xóa liên hệ">
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
            $('body').on('click', '.btn-mark-read', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const isRead = $(this).hasClass('btn-primary');
                const url = isRead ?
                    "{{ route('contact.mark-read', ['id' => ':id']) }}".replace(':id', id) :
                    "{{ route('contact.mark-unread', ['id' => ':id']) }}".replace(':id', id);
                const $button = $(this);
                const $row = $button.closest('tr');
                const $statusBadge = $row.find('.status-badge');
                const $markIcon = $button.find('.mark-icon');

                console.log('Nút đánh dấu được nhấn, ID:', id, 'isRead:', isRead, 'URL:', url);
                console.log('Status Badge found:', $statusBadge.length, 'Mark Icon found:', $markIcon.length);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('AJAX thành công:', response);
                        if (response.success) {
                            toastr.success(response.message);
                            console.log('Cập nhật trạng thái, isRead:', isRead);

                            if (isRead) {
                                $statusBadge.removeClass('badge-warning').addClass('badge-success').text('Đã đọc');
                                $button.removeClass('btn-primary').addClass('btn-secondary');
                                $markIcon.removeClass('fa-envelope-open').addClass('fa-envelope');
                                $button.attr('title', 'Đánh dấu chưa đọc');
                            } else {
                                $statusBadge.removeClass('badge-success').addClass('badge-warning').text('Chưa đọc');
                                $button.removeClass('btn-secondary').addClass('btn-primary');
                                $markIcon.removeClass('fa-envelope').addClass('fa-envelope-open');
                                $button.attr('title', 'Đánh dấu đã đọc');
                            }
                        } else {
                            console.log('Response không thành công:', response);
                            toastr.error('Không thể cập nhật trạng thái');
                        }
                    },
                    error: function(xhr) {
                        console.log('AJAX lỗi:', xhr.status, xhr.responseText);
                        toastr.error('Có lỗi xảy ra khi thay đổi trạng thái');
                    }
                });
            });

            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa liên hệ?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Gửi yêu cầu xóa, ID:', id);
                        $.ajax({
                            url: "{{ route('contact.destroy', ':id') }}".replace(':id', id),
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log('Xóa thành công:', response);
                                toastr.success(response.message);
                                $('#contact-' + id).remove();
                            },
                            error: function(xhr) {
                                console.log('Lỗi khi xóa:', xhr.status, xhr.responseText);
                                toastr.error('Có lỗi khi xóa liên hệ');
                            }
                        });
                    }
                });
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        });
    </script>
@endsection