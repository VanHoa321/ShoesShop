@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('admin-post.index') }}" class="text-info">Danh sách bài viết</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa bài viết</li>
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
                            <form method="post" action="{{ route('admin-post.update', $edit->id) }}" id="quickForm">
                                @csrf
                                @method('PUT')
                                <div class="card-body">                                                               
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="{{ $edit->image }}" style="width:320px; height:220px;" class="mx-auto d-block mb-4" />
                                                <span class="input-group-btn mr-2">
                                                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                        <i class="fa-solid fa-image"></i> Chọn ảnh
                                                    </a>
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="image" value="{{ old('image', $edit->image) }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tiêu đề</label>
                                                        <input type="text" name="title" value="{{ old('title', $edit->title) }}" class="form-control" placeholder="Nhập tiêu đề">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phân loại bài viết</label>
                                                        <select name="tags[]" class="form-control custom-select2" multiple>
                                                            @foreach($tags as $tag)
                                                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $edit->tags->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>                         
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả ngắn</label>
                                                <textarea class="form-control mb-3" name="abstract" placeholder="Nhập mô tả ngắn" style="height: 109px">{{ old('abstract', $edit->abstract) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nội dung</label>
                                        <textarea id="summernote" class="form-control" name="content" placeholder="Nhập nội dung bài viết">{{ old('content', $edit->content) }}</textarea>
                                    </div>                                                                                                                                                                                                                 
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('admin-post.index') }}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $(function () {
            $('#quickForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 5,
                        maxlength: 255
                    },
                    image: {
                        required: true
                    },
                    content: {
                        required: true
                    },
                    'tags[]': {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "Tiêu đề bài viết không được để trống",
                        minlength: "Tiêu đề phải có ít nhất {0} ký tự!",
                        maxlength: "Tiêu đề tối đa {0} ký tự!"
                    },
                    image: {
                        required: "Vui lòng chọn hình ảnh cho bài viết"
                    },
                    content: {
                        required: "Nội dung bài viết không được để trống"
                    },
                    'tags[]': {
                        required: "Vui lòng chọn ít nhất một phân loại"
                    }
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

            $('.select2').select2();
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('.custom-select2').select2({
                theme: 'bootstrap4',
                closeOnSelect: false,
            });

            $('#summernote').summernote({
                height: 300
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        });
    </script>
@endsection