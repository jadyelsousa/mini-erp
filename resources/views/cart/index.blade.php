@extends('layouts.app')

@section('title', 'Carrinho')

@section('content')
    <h1>Carrinho de Compras</h1>
    @if (!empty($cart))
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Variação</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['variation'] ?? 'N/D' }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>R${{ number_format($item['price'], 2, ',', '.') }}</td>
                                <td>R${{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p><strong>Subtotal:</strong> R${{ number_format($subtotal, 2, ',', '.') }}</p>
                <p><strong>Frete:</strong> R${{ number_format($freight, 2, ',', '.') }}</p>
                @if ($coupon)
                    <p><strong>Desconto do Cupom ({{ $coupon->code }}):</strong> R${{ number_format($coupon->discount, 2, ',', '.') }}</p>
                @endif
                <p><strong>Total:</strong> R${{ number_format($subtotal + $freight - ($coupon ? $coupon->discount : 0), 2, ',', '.') }}</p>
            </div>
        </div>

        <!-- Coupon Form -->
        <div class="card mt-3">
            <div class="card-body">
                <h5>Aplicar Cupom</h5>
                <form action="{{ route('coupon.apply') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="code" id="code" class="form-control" placeholder="Digite o código do cupom">
                        <button type="submit" class="btn btn-primary">Aplicar</button>
                    </div>
                </form>
            </div>
        </div>

        <a href="{{ route('checkout.index') }}" class="btn btn-success mt-3">Finalizar Compra</a>
    @else
        <div class="card">
            <div class="card-body">
                <p>Seu carrinho está vazio.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Comprar Agora</a>
            </div>
        </div>
    @endif
@endsection
