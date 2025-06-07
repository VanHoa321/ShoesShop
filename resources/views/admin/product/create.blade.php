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
                            <form method="post" action="{{route("product.store")}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="row">
                                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="" style="width:230px; height:230px; object-fit:cover;" class="mx-auto d-block mb-4" />
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
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Thương hiệu</label>
                                                        <select name="branch_id" class="form-control select2bs4" style="width: 100%">
                                                            @foreach($brands as $item)
                                                                <option value="{{$item->id}}" {{ old('branch_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Giá bán</label>
                                                        <input type="number" name="price" class="form-control" placeholder="VD: 100000" value="{{old('price')}}">
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
                                                <div class="mb-3">
                                                    <label class="ml-2">Chọn màu sắc</label>
                                                    <div id="colorSelectionButtons" class="container-fluid">
                                                        @foreach($colors->chunk(6) as $colorRow)
                                                            <div class="row mb-2">
                                                                @foreach($colorRow as $color)
                                                                    <div class="col-md-2 col-sm-4 col-6"> 
                                                                        <button type="button" class="btn btn-outline-secondary w-100 color-btn"
                                                                            data-color-id="{{ $color->id }}"
                                                                            data-color-code="{{ $color->code }}"
                                                                            style="background-color: {{ $color->code }};
                                                                                color: {{ (hexdec(substr($color->code, 1, 2)) + hexdec(substr($color->code, 3, 2)) + hexdec(substr($color->code, 5, 2))) / 3 > 128 ? 'black' : 'white' }};
                                                                                border: 1px solid #ccc;">
                                                                            {{ $color->name }}
                                                                            <span class="selection-icon" style="display:none; margin-left: 5px;"><i class="fa fa-check-circle"></i></span>
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <input type="hidden" name="selected_color_ids" id="selectedColorIdsInput">
                                                </div>
                                                <div id="variantContainer"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nội dung</label>
                                        <textarea id="summernote" class="form-control" name="content" placeholder="Nhập nội dung bài viết">{{ old('content') }}</textarea>
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
@section('scripts')
    <script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-validation/additional-methods.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

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
                    price: {
                        required: true,
                        min: 0
                    },
                    branch_id: {
                        required: true
                    },
                    'categories[]': {
                        required: true
                    },
                    summary: {
                        required: true,
                        minlength: 10
                    },                  
                    selected_color_ids: {
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
                    price: {
                        required: "Giá bán không được để trống!",
                        min: "Giá bán phải là số dương!"
                    },
                    branch_id: {
                        required: "Vui lòng chọn thương hiệu!"
                    },
                    'categories[]': {
                        required: "Vui lòng chọn ít nhất một danh mục!"
                    },
                    summary: {
                        required: "Mô tả ngắn không được để trống!",
                        minlength: "Mô tả ngắn phải có ít nhất {0} ký tự!"
                    },
                    selected_color_ids: {
                        required: "Vui lòng chọn ít nhất một màu sắc!"
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

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 5000);

            var selectedColorIds = [];

            function updateSelectedColorsAndVariants() {
                $('#selectedColorIdsInput').val(selectedColorIds.join(','));
                validator.element("#selectedColorIdsInput"); 
                generateVariantInputs(selectedColorIds);
            }

            $('#colorSelectionButtons').on('click', '.color-btn', function () {
                var colorId = $(this).data('color-id');
                var $selectionIcon = $(this).find('.selection-icon');
                var index = selectedColorIds.indexOf(colorId);

                if (index > -1) {
                    selectedColorIds.splice(index, 1);
                    $(this).removeClass('btn-info').addClass('btn-outline-secondary');
                    $selectionIcon.hide();
                } else {
                    selectedColorIds.push(colorId);
                    $(this).removeClass('btn-outline-secondary').addClass('btn-info');
                    $selectionIcon.show();
                }
                updateSelectedColorsAndVariants();
            });

            function generateVariantInputs(selectedColors) {
                var $variantContainer = $('#variantContainer');
                $variantContainer.empty();

                if (selectedColors.length > 0) {
                    var sizes = @json($sizes);

                    selectedColors.forEach(function (colorId) {
                        var colorButton = $('#colorSelectionButtons .color-btn[data-color-id="' + colorId + '"]');
                        var colorName = colorButton.text().replace(/(\s*<span.*?span>\s*)/g, '').trim();
                        var colorCode = colorButton.data('color-code');

                        var variantHtml = `
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Màu: ${colorName} <span style="display:inline-block;width:20px;height:20px;background-color:${colorCode};border:1px solid #ccc; vertical-align: middle;"></span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="mb-2">Chọn size</label>
                                        <div class="row size-buttons-row">`;

                        sizes.forEach(function (size) {
                            variantHtml += `
                                <div class="col-md-3 col-sm-4 col-6 mb-2"> 
                                    <button type="button" class="btn btn-outline-secondary w-100 size-btn"
                                        data-color-id="${colorId}"
                                        data-size-id="${size.id}"
                                        data-size-name="${size.name}">
                                        ${size.name}
                                        <span class="selection-icon" style="display:none; margin-left: 5px;"><i class="fa fa-check-circle"></i></span>
                                    </button>
                                    <div class="form-group mt-2 variant-details" id="variant_details_${colorId}_${size.id}" style="display:none;">
                                        <label class="mb-1">Số lượng</label>
                                        <input type="number" data-color-id="${colorId}" data-size-id="${size.id}" class="form-control mb-2" placeholder="VD: 100">
                                        <label class="mb-1">Giá bán</label>
                                        <input type="number" data-color-id="${colorId}" data-size-id="${size.id}" class="form-control mb-2" placeholder="VD: 100000">
                                        <label class="mb-1">Giảm giá (%)</label>
                                        <input type="number" data-color-id="${colorId}" data-size-id="${size.id}" class="form-control mb-2" placeholder="VD: 10">
                                        <input type="hidden" data-color-id="${colorId}" data-size-id="${size.id}" class="selected-input" value="0">
                                    </div>
                                </div>`;
                        });

                        variantHtml += `
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label class="mb-2">Ảnh mô tả màu sắc này</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a id="lfm-color-img-${colorId}" data-input="color_images_${colorId}" data-preview="color-img-holder-${colorId}" class="btn btn-info lfm2-btn">
                                                    <i class="fa fa-picture-o"></i> Chọn ảnh
                                                </a>
                                            </span>
                                            <input id="color_images_${colorId}" class="form-control" type="hidden" name="color_images[${colorId}]">
                                        </div>
                                        <div id="color-img-holder-${colorId}" class="mt-2 d-flex flex-wrap"></div>
                                    </div>
                                </div>
                            </div>`;
                        $variantContainer.append(variantHtml);

                        $(`#lfm-color-img-${colorId}`).on('click', function () {
                            var inputId = $(this).data('input');
                            var previewId = $(this).data('preview');
                            lfm2({ type: 'image', prefix: '/files-manager' }, function(items) {
                                var file_paths = items.map(function (item) {
                                    return item.url;
                                });
                                $(`#${inputId}`).val(file_paths.join(','));
                                var $holder = $(`#${previewId}`);
                                $holder.empty();
                                file_paths.forEach(function(url) {
                                    var $img = $('<img>').attr({
                                        src: url,
                                        style: 'width: 160px; height: 160px; margin-right: 5px; margin-bottom: 5px; border: 1px solid #ddd; object-fit: cover;'
                                    });
                                    $holder.append($img);
                                });
                                validator.element(`#${inputId}`);
                            });
                        });
                    });

                    $('.size-btn').off('click').on('click', function () {
                        var colorId = $(this).data('color-id');
                        var sizeId = $(this).data('size-id');
                        var $details = $(`#variant_details_${colorId}_${sizeId}`);

                        var $quantityInput = $details.find(`input[data-color-id="${colorId}"][data-size-id="${sizeId}"][type="number"].form-control:eq(0)`);
                        var $priceInput = $details.find(`input[data-color-id="${colorId}"][data-size-id="${sizeId}"][type="number"].form-control:eq(1)`);
                        var $discountInput = $details.find(`input[data-color-id="${colorId}"][data-size-id="${sizeId}"][type="number"].form-control:eq(2)`);
                        var $selectedInput = $details.find(`input[data-color-id="${colorId}"][data-size-id="${sizeId}"][type="hidden"]`);

                        var $selectionIcon = $(this).find('.selection-icon');

                        if ($(this).hasClass('btn-info')) {
                            $(this).removeClass('btn-info').addClass('btn-outline-secondary');
                            $details.hide();
                            $selectedInput.val('0');
                            $selectionIcon.hide();

                            $quantityInput.rules('remove');
                            $priceInput.rules('remove');
                            $discountInput.rules('remove');

                            $quantityInput.removeAttr('name').val('');
                            $priceInput.removeAttr('name').val('');
                            $discountInput.removeAttr('name').val('');
                            $selectedInput.removeAttr('name');

                            validator.element($quantityInput);
                            validator.element($priceInput);
                            validator.element($discountInput);
                        } else {
                            $(this).removeClass('btn-outline-secondary').addClass('btn-info');
                            $details.show();
                            $selectedInput.val('1');
                            $selectionIcon.show();

                            $quantityInput.attr('name', `variants[${colorId}][${sizeId}][quantity]`);
                            $priceInput.attr('name', `variants[${colorId}][${sizeId}][price]`);
                            $discountInput.attr('name', `variants[${colorId}][${sizeId}][discount]`);
                            $selectedInput.attr('name', `variants[${colorId}][${sizeId}][selected]`);

                            $quantityInput.rules('add', {
                                required: true,
                                min: 0,
                                messages: {
                                    required: "Số lượng không được để trống!",
                                    min: "Số lượng phải lớn hơn hoặc bằng 0!"
                                }
                            });
                            $priceInput.rules('add', {
                                required: true,
                                min: 0,
                                messages: {
                                    required: "Giá bán không được để trống!",
                                    min: "Giá bán phải lớn hơn hoặc bằng 0!"
                                }
                            });
                            $discountInput.rules('add', {
                                min: 0,
                                max: 100,
                                messages: {
                                    min: "Giảm giá phải từ 0 đến 100!",
                                    max: "Giảm giá phải từ 0 đến 100!"
                                }
                            });

                            validator.element($quantityInput);
                            validator.element($priceInput);
                            validator.element($discountInput);
                        }
                    });
                }
            }
        });
    </script>
@endsection