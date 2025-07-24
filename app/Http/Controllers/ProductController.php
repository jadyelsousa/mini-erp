<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('stock')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'variation' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0|max:99999',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'variation' => $request->variation,
        ]);

        Stock::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso.');
    }

    public function edit(Product $product)
    {
        return view('products.create', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'variation' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0|max:99999',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'variation' => $request->variation,
        ]);

        $product->stock()->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso.');
    }
}
