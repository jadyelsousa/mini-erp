@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <h1>Produtos</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Adicionar Novo Produto</a>

    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">
                            Preço: R${{ number_format($product->price, 2, ',', '.') }}<br>
                            Variação: {{ $product->variation ?? 'N/D' }}<br>
                            Estoque: {{ $product->stock->quantity }}
                            @if ($product->stock->quantity < 5)
                                <span class="text-danger">(Estoque baixo!)</span>
                            @endif
                        </p>
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock->quantity }}" class="form-control me-2" style="width: 100px;">
                            <button type="submit" class="btn btn-success">Adicionar ao Carrinho</button>
                        </form>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning mt-2">Editar</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
