<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Services\FreightService;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $coupon = session('coupon');
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $freight =  FreightService::calculateFreight($subtotal);

        return view('checkout.index', compact('cart', 'coupon', 'subtotal', 'freight'));
    }

    public function storeCheckout(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'cep' => 'required|string|size:8',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:50',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('checkout.index')->withErrors(['error' => 'Carrinho está vazio.']);
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $freight = FreightService::calculateFreight($subtotal);
        $coupon = session('coupon');
        $discount = $coupon ? $coupon->discount : 0;
        $total = $subtotal + $freight - $discount;

        if ($total > 999999.99) {
            return redirect()->route('checkout.index')->withErrors(['error' => 'Total do pedido excede o limite máximo permitido.']);
        }

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock->quantity < $item['quantity']) {
                return redirect()->route('checkout.index')->withErrors(['error' => "Estoque insuficiente para {$item['name']}."]);
            }
            $product->stock->decrement('quantity', $item['quantity']);
        }

        $order = Order::create([
            'subtotal' => $subtotal,
            'freight' => $freight,
            'total' => $total,
            'coupon_id' => $coupon ? $coupon->id : null,
            'status' => 'pending',
            'email' => $request->email,
            'cep' => $request->cep,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'variation' => $item['variation'],
            ]);
        }

        session()->forget(['cart', 'coupon']);

        try {
            Mail::to($request->email)->send(new OrderConfirmation($order, $cart));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->withErrors(['error' => 'Pedido realizado com sucesso. Mas houve um erro ao enviar o e-mail de confirmação: ' . $e->getMessage()]);
        }


        return redirect()->route('products.index')->with('success', 'Pedido realizado com sucesso. Um e-mail de confirmação foi enviado.');
    }
}
