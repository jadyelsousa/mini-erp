@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
    <h1>Finalizar Compra</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (!empty($cart))
        <div class="card">
            <div class="card-body">
                <h5>Resumo do Pedido</h5>
                <p><strong>Subtotal:</strong> R${{ number_format($subtotal, 2, ',', '.') }}</p>
                <p><strong>Frete:</strong> R${{ number_format($freight, 2, ',', '.') }}</p>
                @if ($coupon)
                    <p><strong>Desconto do Cupom ({{ $coupon->code }}):</strong> R${{ number_format($coupon->discount, 2, ',', '.') }}</p>
                @endif
                <p><strong>Total:</strong> R${{ number_format($subtotal + $freight - ($coupon ? $coupon->discount : 0), 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5>Endereço de Entrega</h5>
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control" value="{{ old('cep') }}" required>
                        @error('cep')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="street" class="form-label">Rua</label>
                        <input type="text" name="street" id="street" class="form-control" value="{{ old('street') }}" required>
                        @error('street')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="number" class="form-label">Número</label>
                        <input type="text" name="number" id="number" class="form-control" value="{{ old('number') }}" required>
                        @error('number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="complement" class="form-label">Complemento (opcional)</label>
                        <input type="text" name="complement" id="complement" class="form-control" value="{{ old('complement') }}">
                        @error('complement')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="neighborhood" class="form-label">Bairro</label>
                        <input type="text" name="neighborhood" id="neighborhood" class="form-control" value="{{ old('neighborhood') }}" required>
                        @error('neighborhood')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">Cidade</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" required>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">Estado</label>
                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}" required>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmar Pedido</button>
                    <a href="{{ route('cart.index') }}" class="btn btn-secondary">Voltar ao Carrinho</a>
                </form>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <p>Seu carrinho está vazio.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Comprar Agora</a>
            </div>
        </div>
    @endif

    @section('scripts')
        <script>
            document.getElementById('cep').addEventListener('blur', function() {
                const cep = this.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById('street').value = data.logradouro;
                                document.getElementById('neighborhood').value = data.bairro;
                                document.getElementById('city').value = data.localidade;
                                document.getElementById('state').value = data.uf;
                            } else {
                                alert('CEP inválido');
                            }
                        })
                        .catch(() => alert('Erro ao buscar dados do CEP'));
                }
            });
        </script>
    @endsection
@endsection
