@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('brand.index') }}" class="text-info">Quản lý thương hiệu </a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa thông tin thương hiệu</li>
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
                        <div class="card card-primary">
                            <form method="post" action="{{route("brand.update", $edit->id)}}" id="quickForm">
                                @csrf
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 d-flex justify-content-center align-items-center">
                                        <div class="form-group text-center mt-2">
                                            <img id="holder" src="" style="width:280px; height:150px; object-fit:cover;" class="mx-auto d-block mb-4" />
                                            <span class="input-group-btn mr-2">
                                                <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                    <i class="fa-solid fa-image"></i> Chọn ảnh bìa
                                                </a>
                                            </span>
                                            <input id="thumbnail" class="form-control" type="hidden" name="image" value="{{ old('image', $edit->image) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Tên thương hiệu</label>
                                            <input type="text" name="name" value="{{ old('name', $edit->name) }}" class="form-control" placeholder="VD: Adidas, Nike, Puma..." required>
                                        </div>
                                        <div class="form-group">
                                            <label>Mô tả thêm</label>
                                            <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm (nếu cần)" style="height: 100px">{{ old('description', $edit->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>                                                                                                 
                                <div class="card-footer">
                                    <a href="{{route('brand.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js")}}"></script>

    <script>
        $(function () {
            $('#quickForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 255
                },
            },
            messages: {
                name: {
                    required: "Tên thương hiệu không được để trống!",
                    minlength: "Tên thương hiệu phải có ít nhất 2 ký tự!",
                    maxlength: "Tên thương hiệu không được quá 255 ký tự!"
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
    </script>
@endsection
