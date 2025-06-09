@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('product.create') }}" class="text-info">Quản lý sản phẩm</a></li>
                            <li class="breadcrumb-item active">Thêm mới sản phẩm</li>
                        </ol>               
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-x-circle-fill text-danger"></i> {{ $error }}
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
                            <form method="post" action="{{route('product.store')}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="row">
                                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="{{ old('thumbnail') ?: '' }}" style="width:230px; height:230px; object-fit:cover;" class="mx-auto d-block mb-4" />
                                                <span class="input-group-btn mr-2">
                                                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                        <i class="fa-solid fa-image"></i> Chọn ảnh đại diện
                                                    </a>
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="thumbnail" value="{{ old('thumbnail') }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tên sản phẩm</label>
                                                        <input type="text" name="name" class="form-control" placeholder="VD: Giày Thể Thao Helio Teen Nam Màu Trắng" value="{{old('name')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Mã sản phẩm</label>
                                                        <input type="text" name="code" class="form-control" placeholder="VD: BSB008100TRG" value="{{old('code')}}">
                                                    </div>
                                                </div>
                                            </div>                                                                         
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Thương hiệu</label>
                                                        <select name="brand_id" class="form-control select2bs4" style="width: 100%">
                                                            @foreach($brands as $item)
                                                                <option value="{{$item->id}}" {{ old('brand_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Giá bán</label>
                                                        <input type="number" name="price" class="form-control" placeholder="VD: 100000" value="{{old('price')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Giảm giá</label>
                                                        <input type="number" name="discount" class="form-control" placeholder="VD: 10000" value="{{old('discount')}}">
                                                        <em class="text-muted">Nếu không giảm giá, để trống hoặc nhập 0</em>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Màu sắc</label>
                                                        <select name="color_id" class="form-control color-select2" style="width: 100%">
                                                            @foreach($colors as $color)
                                                                <option value="{{ $color->id }}" data-color-code="{{ $color->code }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                                    {{ $color->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Mã nhóm sản phẩm</label>
                                                        <input type="text" name="group_code" class="form-control" placeholder="VD: SP001" value="{{old('group_code')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Danh mục sản phẩm</label>
                                                        <select name="categories[]" class="form-control custom-select2" multiple>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" data-name="{{ $category->name }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div> 
                                                <div class="col-md-6">
                                                    <div class="form-group d-flex justify-content-between align-items-center" style="margin-top: 34px;">
                                                        <div class="icheck-success" style="width: 33%;">
                                                            <input type="checkbox" name="is_new" id="checkboxSuccess1" value="1" {{ old('is_new') ? 'checked' : '' }}>
                                                            <label for="checkboxSuccess1">Mới</label>
                                                        </div>
                                                        <div class="icheck-success" style="width: 40%;">
                                                            <input type="checkbox" name="is_sale" id="checkboxSuccess2" value="1" {{ old('is_sale') ? 'checked' : '' }}>
                                                            <label for="checkboxSuccess2">Giảm giá</label>
                                                        </div>
                                                        <div class="icheck-success" style="width: 33%;">
                                                            <input type="checkbox" name="is_bestseller" id="checkboxSuccess3" value="1" {{ old('is_bestseller') ? 'checked' : '' }}>
                                                            <label for="checkboxSuccess3">Nổi bật</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                 
                                            <div class="form-group">
                                                <label>Mô tả ngắn</label>
                                                <textarea class="form-control mb-1" name="summary" placeholder="VD: Mang tinh thần tối giản nhưng vẫn nổi bật, mẫu giày sneaker Helio BSG007400 màu kem phối nâu là lựa chọn lý tưởng cho các bạn tuổi teen yêu thích phong cách thanh lịch, hiện đại và dễ phối đồ." style="height: 100px">{{ old('summary') }}</textarea>
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="mb-2">Chọn size giày</label>
                                                <div class="row" id="sizeInputsContainer">
                                                    @foreach($sizes as $size)
                                                        @php
                                                            $oldSizeData = old('sizes.' . $size->id);
                                                            $isSizeSelected = $oldSizeData && ($oldSizeData['selected'] ?? '0') == '1';
                                                        @endphp
                                                        <div class="col-md-2 col-sm-6 mb-3">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <button type="button" class="btn btn-outline-secondary w-100 size-toggle-btn {{ $isSizeSelected ? 'btn-info' : '' }}"
                                                                        data-size-id="{{ $size->id }}">
                                                                        Size: {{ $size->name }}
                                                                        <span class="selection-icon" style="display:{{ $isSizeSelected ? 'inline' : 'none' }}; margin-left: 5px;"><i class="fa fa-check-circle"></i></span>
                                                                    </button>
                                                                </div>
                                                                <input type="hidden" name="sizes[{{ $size->id }}][selected]" value="{{ $isSizeSelected ? '1' : '0' }}" class="size-selected-input">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div id="image-upload-area" class="d-flex flex-wrap align-items-center justify-content-center p-3" 
                                            style="min-height: 150px; position: relative; border: 2px dashed #007bff; border-radius: 8px;">
                                            
                                            <a id="lfm-color-gallery" 
                                                data-input="color_images_input" 
                                                data-preview="color-images-holder" 
                                                class="lfm2-btn d-flex flex-column align-items-center justify-content-center" 
                                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: none; border: none; padding: 0; font-size: 4em; color: #007bff; cursor: pointer; text-align: center;">
                                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                                <em class="text-muted mt-2" style="font-size: 0.9rem;">Tải lên ảnh mô tả sản phẩm</em>
                                            </a>

                                            <div id="color-images-holder" 
                                                class="d-flex flex-wrap justify-content-center align-items-center" 
                                                style="width: 100%; height: 100%;">
                                                @if(old('color_images'))
                                                    @php
                                                        $oldColorImagePaths = explode(',', old('color_images'));
                                                    @endphp
                                                    @foreach($oldColorImagePaths as $path)
                                                        <div class="uploaded-image-item" style="position: relative; margin: 5px; border: 1px solid #ddd;">
                                                            <img src="{{ $path }}" style="width: 120px; height: 120px; object-fit: cover;">
                                                            <button type="button" class="btn btn-danger btn-sm remove-image-btn" data-url="{{ $path }}" 
                                                                    style="position: absolute; top: 0; right: 0; padding: 2px 5px; border-radius: 0 0 0 5px;">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>

                                            <input id="color_images_input" class="form-control" type="hidden" name="color_images" value="{{ old('color_images') }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Nội dung</label>
                                        <textarea id="summernote" class="form-control" name="description" placeholder="Nhập nội dung bài viết">{{ old('description') }}</textarea>
                                    </div>  
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('product.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
@section('styles')
    <style>
        .hidden-upload-btn {
            display: none !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-validation/additional-methods.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>

    <script>
        var lfm2 = function(options, cb) {
            var route_prefix = (options && options.prefix) ? options.prefix : '/files-manager';
            window.open(route_prefix + '?type=' + (options.type || 'image') + '&multiple=1', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        $(function () {
            var validator = $('#quickForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    code: {
                        required: true,
                        minlength: 3
                    },
                    group_code: {
                        required: true,
                        minlength: 3
                    },
                    price: {
                        required: true,
                        min: 0
                    },
                    brand_id: {
                        required: true
                    },
                    'categories[]': {
                        required: true
                    },
                    summary: {
                        required: true,
                        minlength: 10
                    },
                    'sizes[]': {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Tên sản phẩm không được để trống!",
                        minlength: "Tên sản phẩm phải có ít nhất {0} ký tự!"
                    },
                    code: {
                        required: "Mã sản phẩm không được để trống!",
                        minlength: "Mã sản phẩm phải có ít nhất {0} ký tự!"
                    },
                    group_code: {
                        required: "Mã nhóm sản phẩm không được để trống!",
                        minlength: "Mã nhóm sản phẩm phải có ít nhất {0} ký tự!"
                    },
                    price: {
                        required: "Giá bán không được để trống!",
                        min: "Giá bán phải là số dương!"
                    },
                    brand_id: {
                        required: "Vui lòng chọn thương hiệu!"
                    },
                    'categories[]': {
                        required: "Vui lòng chọn ít nhất một danh mục!"
                    },
                    summary: {
                        required: "Mô tả ngắn không được để trống!",
                        minlength: "Mô tả ngắn phải có ít nhất {0} ký tự!"
                    },
                    'sizes[]': {
                        required: "Vui lòng chọn ít nhất một size!"
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
            }).on('change', function () {
                validator.element($(this));
            });

            $('.color-select2').select2({
                theme: 'bootstrap4',
                templateResult: function (data) {
                    if (!data.id) return data.text;

                    var colorCode = $(data.element).data('color-code');
                    var colorName = data.text;

                    return $(`
                        <div style="display: flex; align-items: center;">
                            <span style="display:inline-block;width:20px;height:20px;background-color:${colorCode};border:1px solid #ccc;margin-right:8px;"></span>
                            <span>
                                ${colorName} <span style="font-size:0.8em;color:#666;">(${colorCode})</span>
                            </span>
                        </div>
                    `);
                },
                templateSelection: function (data) {
                    if (!data.id) return data.text;

                    var colorCode = $(data.element).data('color-code');
                    var colorName = data.text;

                    return $(`
                        <div style="display: flex; align-items: center;">
                            <span style="display:inline-block;width:20px;height:20px;background-color:${colorCode};border:1px solid #ccc;margin-right:8px;"></span>
                            <span>${colorName}</span>
                        </div>
                    `);
                },
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    var colorCode = $(data.element).data('color-code') || '';
                    var text = data.text.toLowerCase();
                    var code = colorCode.toLowerCase();
                    var term = params.term.toLowerCase();

                    if (text.includes(term) || code.includes(term)) {
                        return data;
                    }

                    return null;
                }
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 5000);

            function updateImageUploadButtonVisibility() {
                var imageInputValue = $('#color_images_input').val();
                var $lfmButton = $('#lfm-color-gallery');

                if (imageInputValue && imageInputValue.trim() !== '') {
                    $lfmButton.addClass('hidden-upload-btn');
                } else {
                    $lfmButton.removeClass('hidden-upload-btn');
                }
            }

            $('#lfm-color-gallery').on('click', function () {
                var inputId = $(this).data('input');
                var previewId = $(this).data('preview');

                lfm2({ type: 'image', prefix: '/files-manager' }, function (items) {
                    var file_paths = items.map(item => item.url).filter(url => url); // Filter out empty URLs
                    $('#color_images_input').val(file_paths.join(','));

                    var $holder = $(`#${previewId}`);
                    $holder.empty();

                    file_paths.forEach(function (url) {
                        var $wrapper = $('<div class="uploaded-image-item" style="position: relative; margin: 5px; border: 1px solid #ddd;">');
                        var $img = $('<img>').attr({
                            src: url,
                            style: 'width: 160px; height: 160px; object-fit: cover;'
                        });
                        var $removeBtn = $('<button type="button" class="btn btn-danger btn-sm remove-image-btn" style="position: absolute; top: 0; right: 0; padding: 2px 5px; border-radius: 0 0 0 5px;">')
                            .html('<i class="fa fa-trash"></i>')
                            .data('url', url);
                        $wrapper.append($img).append($removeBtn);
                        $holder.append($wrapper);
                    });

                    updateImageUploadButtonVisibility();
                    validator.element(`#${inputId}`);
                });
            });

            // Xử lý xóa ảnh khỏi UI và hidden input
            $(document).on('click', '.remove-image-btn', function () {
                var urlToRemove = $(this).data('url');
                var $input = $('#color_images_input');
                var currentPaths = $input.val().split(',').filter(Boolean);

                var updatedPaths = currentPaths.filter(path => path !== urlToRemove);
                $input.val(updatedPaths.join(','));

                $(this).closest('.uploaded-image-item').remove();

                updateImageUploadButtonVisibility();
                validator.element('#color_images_input');
            });

            // Toggle size
            $('.size-toggle-btn').on('click', function () {
                var sizeId = $(this).data('size-id');
                var $selectionIcon = $(this).find('.selection-icon');
                var $input = $(`input[name="sizes[${sizeId}][selected]"]`);

                if ($(this).hasClass('btn-info')) {
                    $(this).removeClass('btn-info').addClass('btn-outline-secondary');
                    $selectionIcon.hide();
                    $input.val('0');
                } else {
                    $(this).removeClass('btn-outline-secondary').addClass('btn-info');
                    $selectionIcon.show();
                    $input.val('1');
                }

                // Validate sizes
                let selectedSizes = $('input[name^="sizes"][name$="[selected]"]').filter(function() {
                    return $(this).val() === '1';
                }).length;
                validator.element('#sizeInputsContainer');
            });

            $('#color_images_input').on('change', function() {
                updateImageUploadButtonVisibility();
            });
        });
    </script>
@endsection