<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index()
    {
        $items = Voucher::orderBy('id', 'desc')->get();
        return view('admin.voucher.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'code' => 'required|string|max:50|unique:vouchers,code' . ($id ? ",$id,id" : ''),
            'type' => 'required|integer|in:1,2',
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('type') == 2 && $value >= 100) {
                        $fail('Giá trị phần trăm phải nhỏ hơn 100!');
                    }
                },
            ],
            'quantity' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ];

        $messages = [
            'code.required' => 'Mã voucher không được để trống!',
            'code.max' => 'Mã voucher tối đa :max ký tự!',
            'code.unique' => 'Mã voucher đã tồn tại!',

            'type.required' => 'Loại voucher không được để trống!',
            'type.integer' => 'Loại voucher không hợp lệ!',
            'type.in' => 'Loại voucher không hợp lệ!',

            'discount_value.required' => 'Giá trị giảm giá không được để trống!',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số!',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0!',

            'quantity.required' => 'Số lượng voucher không được để trống!',
            'quantity.integer' => 'Số lượng voucher phải là số nguyên!',
            'quantity.min' => 'Số lượng voucher phải lớn hơn hoặc bằng 0!',

            'start_date.required' => 'Ngày bắt đầu không được để trống!',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ!',

            'end_date.required' => 'Ngày kết thúc không được để trống!',
            'end_date.date' => 'Ngày kết thúc không hợp lệ!',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu!',

            'description.max' => 'Mô tả tối đa :max ký tự!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view('admin.voucher.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $voucherData = [
            "code" => $request->code,
            "type" => $request->type,
            "discount_value" => $request->discount_value,
            "min_order_amount" => $request->min_order_amount ? $request->min_order_amount : 0,
            "quantity" => $request->quantity,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "description" => $request->description,
        ];

        $create = Voucher::create($voucherData);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới voucher thành công"]);
        return redirect()->route("voucher.index");
    }

    public function edit($id)
    {
        $edit = Voucher::findOrFail($id);
        return view('admin.voucher.edit', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $voucherData = [
            "code" => $request->code,
            "type" => $request->type,
            "discount_value" => $request->discount_value,
            "min_order_amount" => $request->min_order_amount ? $request->min_order_amount : 0,
            "quantity" => $request->quantity,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "description" => $request->description,
        ];

        Voucher::where('id', $id)->update($voucherData);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật voucher thành công"]);
        return redirect()->route("voucher.index");
    }

    public function destroy($id)
    {
        $voucher = Voucher::find($id);
        if ($voucher) {
            $voucher->delete();
            return response()->json(['success' => true, 'message' => 'Xóa voucher thành công']);
        } else {
            return response()->json(['success' => false, 'message' => 'Voucher không tồn tại']);
        }
    }

    public function changeStatus($id)
    {
        $voucher = Voucher::find($id);
        if ($voucher) {
            $voucher->status = $voucher->status == 1 ? 0 : 1;
            $voucher->save();
            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        } else {
            return response()->json(['success' => false, 'message' => 'Voucher không tồn tại']);
        }
    }

}
