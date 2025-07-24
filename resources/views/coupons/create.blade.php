@extends('layouts.app')

@section('title', isset($coupon) ? 'Editar Cupom' : 'Criar Cupom')

@section('content')
    <h1>{{ isset($coupon) ? 'Editar Cupom' : 'Criar Cupom' }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($coupon) ? route('coupons.update', $coupon) : route('coupons.store') }}" method="POST">
                @csrf
                @if(isset($coupon))
                    @method('PUT')
                @endif
                <div class="mb-3">
                    <label for="code" class="form-label">Código</label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', isset($coupon) ? $coupon->code : '') }}" required>
                    @error('code')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="discount" class="form-label">Desconto (R$)</label>
                    <input type="text" name="discount" id="discount" class="form-control money-mask" value="{{ old('discount', isset($coupon) ? number_format($coupon->discount, 2, ',', '.') : '') }}" required>
                    @error('discount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="min_value" class="form-label">Valor Mínimo (R$)</label>
                    <input type="text" name="min_value" id="min_value" class="form-control money-mask" value="{{ old('min_value', isset($coupon) ? number_format($coupon->min_value, 2, ',', '.') : '') }}" required>
                    @error('min_value')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="valid_until" class="form-label">Válido Até</label>
                    <input type="datetime-local" name="valid_until" id="valid_until" class="form-control" value="{{ old('valid_until', isset($coupon) ? \Carbon\Carbon::parse($coupon->valid_until)->format('Y-m-d\TH:i') : '') }}" required>
                    @error('valid_until')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="active" class="form-label">Ativo</label>
                    <input type="checkbox" name="active" id="active" {{ old('active', isset($coupon) ? $coupon->active : true) ? 'checked' : '' }}>
                    @error('active')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ isset($coupon) ? 'Atualizar Cupom' : 'Criar Cupom' }}</button>
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Cancelar</a>
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
