@extends('layouts.app')

@section('title', isset($product) ? 'Editar Produto' : 'Criar Produto')

@section('content')
    <h1>{{ isset($product) ? 'Editar Produto' : 'Criar Produto' }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
                @csrf
                @if(isset($product))
                    @method('PUT')
                @endif
                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', isset($product) ? $product->name : '') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Preço</label>
                    <input type="text" name="price" id="price" class="form-control money-mask" value="{{ old('price', isset($product) ? number_format($product->price ?? 0, 2, ',', '.') : '') }}" required>
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="variation" class="form-label">Variação (opcional)</label>
                    <input type="text" name="variation" id="variation" class="form-control" value="{{ old('variation', isset($product) ? $product->variation : '') }}">
                    @error('variation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantidade em Estoque</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', isset($product) ? $product->stock : '') }}" required>
                    @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Atualizar Produto' : 'Criar Produto' }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="{{ asset('js/money-mask.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.money-mask').formatBrazilianReal();
            });
        </script>
    @endsection
@endsection
