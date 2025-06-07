@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('color.index') }}" class="text-info">Quản lý màu sắc</a></li>
                            <li class="breadcrumb-item active">Thêm mới màu sắc</li>
                        </ol>
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-check2 text-danger"></i> {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Điền các trường dữ liệu</h3>
                            </div>
                            <form method="post" action="{{route("color.store")}}" id="quickForm">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tên màu sắc</label>
                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="VD: Đỏ, Xanh dương...">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mã màu</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="color" id="colorPicker" value="{{ old('code', '#FFFFFF') }}" style="width: 60px; height: 38px; border: none; cursor: pointer;">
                                                    <input type="text" name="code" id="colorCodeInput" value="{{ old('code', '#FFFFFF') }}" class="form-control ml-3" style="max-width: 100%;" placeholder="VD: #FF0000">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('color.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" title="Lưu"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{asset("assets/plugins/jquery-validation/jquery.validate.min.js")}}"></script>
    <script src="{{asset("assets/plugins/jquery-validation/additional-methods.min.js")}}"></script>
    <script>
        $(function () {

            $('#colorPicker').on('input', function () {
                $('#colorCodeInput').val($(this).val());
            });

            $('#colorCodeInput').on('input', function () {
                let val = $(this).val();
                if(/^#([0-9A-Fa-f]{3}){1,2}$/.test(val)) {
                    $('#colorPicker').val(val);
                }
            });

            $('#quickForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    code: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Tên màu sắc không được để trống",
                        minlength: "Tên màu sắc phải có ít nhất {0} ký tự!",
                        maxlength: "Tên màu sắc tối đa {0} ký tự!"
                    },
                    code: {
                        required: "Vui lòng chọn mã màu!"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
