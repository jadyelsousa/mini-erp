<?php

namespace App\Http\Controllers;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
    }

     public function store(Request $request)
    {

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount' => 'required|numeric|min:0|max:9999999999.99',
            'min_value' => 'required|numeric|min:0|max:9999999999.99',
            'valid_until' => 'required|date|after:now',
            'active' => 'nullable|in:on',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        Coupon::create([
            'code' => $request->code,
            'discount' => $request->discount,
            'min_value' => $request->min_value,
            'valid_until' => $request->valid_until,
            'active' => $request->active ? true : false,
        ]);

        return redirect()->route('coupons.index')->with('success', 'Cupom criado com sucesso.');

    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.create', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount' => 'required|numeric|min:0|max:999999.99',
            'min_value' => 'required|numeric|min:0|max:999999.99',
            'valid_until' => 'required|date_format:Y-m-d\TH:i|after:now',
            'active' => 'nullable|in:on',
        ]);

        if (!$validated) {
        return redirect()->back()->withErrors($validated)->withInput();
        }

        $coupon->update([
            'code' => $request->code,
            'discount' => $request->discount,
            'min_value' => $request->min_value,
            'valid_until' => $request->valid_until,
            'active' => $request->active ? true : false,
        ]);

        return redirect()->route('coupons.index')->with('success', 'Cupom atualizado com sucesso.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Cupom excluído com sucesso.');
    }
}
