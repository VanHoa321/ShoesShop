@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('contact.index') }}" class="text-info">Quản lý liên hệ</a></li>
                            <li class="breadcrumb-item active">Chi tiết liên hệ</li>
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
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết liên hệ</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Người gửi</label>
                                            <input type="text" class="form-control" value="{{ $contact->user->name ?? 'Khách' }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value="{{ $contact->user->email ?? 'N/A' }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Ngày gửi</label>
                                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <input type="text" class="form-control status-input" value="{{ $contact->is_read ? 'Đã đọc' : 'Chưa đọc' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nội dung</label>
                                            <textarea class="form-control" style="height: 200px" readonly>{{ $contact->message }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @if($contact->user->email ?? false)
                                    <hr>
                                    @if(!$contact->reply_message)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Phản hồi</label>
                                                    <textarea id="replySummernote" class="form-control" placeholder="Nhập nội dung phản hồi"></textarea>
                                                </div>
                                                <div class="text-right">
                                                    <button type="button" class="btn btn-primary btn-send-reply" data-id="{{ $contact->id }}" data-email="{{ $contact->user->email }}">Gửi phản hồi</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($contact->reply_message)
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nội dung phản hồi đã gửi</label>
                                                    <div class="border p-3 rounded" style="background: #f8f9fa;">{!! $contact->reply_message !!}</div>
                                                    <p class="text-muted mt-2">Gửi lúc: {{ \Carbon\Carbon::parse($contact->replied_at)->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning mt-3">
                                        <i class="fa-solid fa-exclamation-triangle"></i> Không thể gửi phản hồi vì liên hệ này không có email.
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('contact.index') }}" class="btn btn-warning">
                                    <i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-delete" data-id="{{ $contact->id }}" title="Xóa liên hệ">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#replySummernote').summernote({
                height: 200,
                placeholder: 'Nhập nội dung phản hồi',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
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
                        $.ajax({
                            url: "{{ route('contact.destroy', ':id') }}".replace(':id', id),
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                window.location.href = "{{ route('contact.index') }}";
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi khi xóa liên hệ');
                            }
                        });
                    }
                });
            });

            $('body').on('click', '.btn-send-reply', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const email = $(this).data('email');
                const replyMessage = $('#replySummernote').summernote('code');
                
                if ($('#replySummernote').summernote('isEmpty')) {
                    toastr.error('Vui lòng nhập nội dung phản hồi');
                    return;
                }

                Swal.fire({
                    title: "Xác nhận gửi phản hồi?",
                    text: "Phản hồi sẽ được gửi đến " + email,
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Gửi",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('contact.reply', ':id') }}".replace(':id', id),
                            type: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                reply_message: replyMessage
                            },
                            beforeSend: function() {
                                $('.btn-send-reply').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...');
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    window.location.reload();
                                } else {
                                    toastr.error(response.message || 'Không thể gửi phản hồi');
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi xảy ra khi gửi phản hồi: ' + (xhr.responseJSON?.message || 'Lỗi không xác định'));
                            },
                            complete: function() {
                                $('.btn-send-reply').prop('disabled', false).html('Gửi phản hồi');
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