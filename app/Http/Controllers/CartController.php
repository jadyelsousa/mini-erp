<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\FreightService;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $coupon = session('coupon');
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $freight = FreightService::calculateFreight($subtotal);

        return view('cart.index', compact('cart', 'coupon', 'subtotal', 'freight'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:1000',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        if ($product->stock->quantity < $request->quantity) {
            return redirect()->route('products.index')->with('error', 'Estoque insuficiente.');
        }

        $cart = session('cart', []);
        $existingQuantity = $cart[$product->id]['quantity'] ?? 0;
        $newQuantity = $existingQuantity + $request->quantity;

        if ($newQuantity > $product->stock->quantity) {
            return redirect()->route('products.index')->with('error', 'Quantidade total excede o estoque disponível.');
        }

        if ($product->price * $newQuantity > 999999.99) {
            return redirect()->route('products.index')->with('error', 'Valor do carrinho excede o limite máximo permitido.');
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'variation' => $product->variation,
            'price' => $product->price,
            'quantity' => $newQuantity,
        ];

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Produto adicionado ao carrinho.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $cart = session('cart', []);
        $productId = $request->input('product_id');

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
            return redirect()->route('cart.index')->with('success', 'Item removido com sucesso.');
        }

        return redirect()->route('cart.index')->withErrors(['error' => 'Item não encontrado no carrinho.']);
    }

    public function applyCoupon(Request $request)
    {
        if (empty($request->code)) {
            return redirect()->route('cart.index')->with('error', 'Código do cupom é obrigatório.');
        }

        $coupon = Coupon::where('code', $request->code)
            ->where('active', true)
            ->where('valid_until', '>=', now())
            ->first();

        if (!$coupon) {
            return redirect()->route('cart.index')->with('error', 'Cupom inválido ou expirado.');
        }

        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        if ($subtotal < $coupon->min_value) {
            return redirect()->route('cart.index')->with('error', "O subtotal do carrinho deve ser pelo menos R\${$coupon->min_value}.");
        }

        session(['coupon' => $coupon]);

        return redirect()->route('cart.index')->with('success', 'Cupom aplicado com sucesso.');
    }
}
