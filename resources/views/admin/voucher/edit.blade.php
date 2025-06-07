@extends('layout/admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">         
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('voucher.index') }}" class="text-info">Quản lý Voucher</a></li>
                        <li class="breadcrumb-item active">Cập nhật Voucher</li>
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
                        <form method="post" action="{{ route('voucher.update', $edit->id) }}" id="voucherForm">
                            @csrf
                            <div class="card-body">                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mã Voucher</label>
                                            <input type="text" name="code" value="{{ old('code', $edit->code) }}" class="form-control" placeholder="VD: TET2025">
                                        </div>
                                    </div>                                       
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Loại giảm giá</label>
                                            <select name="type" class="form-control select2bs4" style="width: 100%">
                                                <option value="1" {{ old('type', $edit->type) == 1 ? 'selected' : '' }}>Theo số tiền</option>
                                                <option value="2" {{ old('type', $edit->type) == 2 ? 'selected' : '' }}>Theo phần trăm</option>
                                            </select>
                                        </div>
                                    </div>                            
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Giá trị giảm</label>
                                            <input type="number" name="discount_value" value="{{ old('discount_value', $edit->discount_value) }}" class="form-control" placeholder="VD: 10000">
                                            <em class="text-muted">Nhập mệnh giá tương ứng với loại giảm giá đã chọn</em>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giá trị đơn hàng tối thiểu để dùng</label>
                                            <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $edit->min_order_amount) }}" class="form-control" placeholder="VD: 100000">
                                            <em class="text-muted">Nếu không nhập, sẽ không giới hạn giá trị đơn hàng tối thiểu</em>
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số lượng sử dụng</label>
                                            <input type="number" name="quantity" value="{{ old('quantity', $edit->quantity) }}" class="form-control" placeholder="VD: 10">
                                            <em class="text-muted">Nhập số lượng sử dụng của mã giảm giá, bỏ trống nếu chỉ muốn số lượng là 1</em>
                                        </div>
                                    </div>                                
                                </div>  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày bắt đầu</label>
                                            <input type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($edit->start_date)->format('Y-m-d')) }}" class="form-control">
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày kết thúc</label>
                                            <input type="date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($edit->end_date)->format('Y-m-d')) }}" class="form-control">
                                        </div>
                                    </div>                                
                                </div>
                                <div class="form-group">
                                    <label>Mô tả thêm</label>
                                    <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả voucher giảm giá (nếu cần)" style="height: 100px">{{ old('description', $edit->description) }}</textarea>
                                </div>             
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('voucher.index') }}" class="btn btn-warning">
                                    <i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk" title="Lưu"></i>
                                </button>
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
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js")}}"></script>

    <script>
        $(function () {
            $('#voucherForm').validate({
                rules: {
                    code: {
                        required: true,
                        minlength: 2
                    },
                    type: {
                        required: true
                    },
                    discount_value: {
                        required: true,
                        min: 1
                    },
                    start_date: {
                        required: true,
                        date: true
                    },
                    end_date: {
                        required: true,
                        date: true
                    }
                },
                messages: {
                    code: {
                        required: "Mã voucher không được để trống",
                        minlength: "Mã voucher phải có ít nhất {0} ký tự"
                    },
                    type: {
                        required: "Vui lòng chọn loại giảm giá"
                    },
                    discount_value: {
                        required: "Vui lòng nhập giá trị giảm",
                        min: "Giá trị giảm phải lớn hơn 0"
                    },
                    start_date: {
                        required: "Vui lòng chọn ngày bắt đầu",
                        date: "Ngày bắt đầu không hợp lệ"
                    },
                    end_date: {
                        required: "Vui lòng chọn ngày kết thúc",
                        date: "Ngày kết thúc không hợp lệ"
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

            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            },3500);
        });
    </script>
@endsection
