@extends('layouts.app')

@section('title', 'Gerenciar Cupons')

@section('content')
    <h1>Gerenciar Cupons</h1>
    <a href="{{ route('coupons.create') }}" class="btn btn-primary mb-3">Criar Novo Cupom</a>

    @if ($coupons->isEmpty())
        <div class="card">
            <div class="card-body">
                <p>Nenhum cupom cadastrado.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Desconto (R$)</th>
                            <th>Valor Mínimo (R$)</th>
                            <th>Válido Até</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->code }}</td>
                                <td>R${{ number_format($coupon->discount, 2, ',', '.') }}</td>
                                <td>R${{ number_format($coupon->min_value, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($coupon->valid_until)->format('d/m/Y H:i') }}</td>
                                <td>{{ $coupon->active ? 'Sim' : 'Não' }}</td>
                                <td>
                                    <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este cupom?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
