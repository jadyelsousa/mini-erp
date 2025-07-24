<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmação do Pedido</title>
</head>
<body>
    <h1>Confirmação do Pedido #{{ $order->id }}</h1>
    <p>Olá,</p>
    <p>Obrigado por sua compra! Seu pedido foi recebido e está sendo processado. Aqui estão os detalhes:</p>

    <h2>Itens do Pedido</h2>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px;">Produto</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Variação</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Quantidade</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Preço</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $item)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item['name'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item['variation'] ?? 'N/D' }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item['quantity'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">R${{ number_format($item['price'], 2, ',', '.') }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">R${{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Resumo do Pedido</h2>
    <p><strong>Subtotal:</strong> R${{ number_format($order->subtotal, 2, ',', '.') }}</p>
    <p><strong>Frete:</strong> R${{ number_format($order->freight, 2, ',', '.') }}</p>
    @if ($order->coupon_id)
        <p><strong>Desconto do Cupom:</strong> R${{ number_format($order->total - $order->subtotal - $order->freight, 2, ',', '.') }}</p>
    @endif
    <p><strong>Total:</strong> R${{ number_format($order->total, 2, ',', '.') }}</p>

    <h2>Endereço de Entrega</h2>
    <p>{{ $order->street }}, {{ $order->number }} {{ $order->complement ? ', ' . $order->complement : '' }}</p>
    <p>{{ $order->neighborhood }}, {{ $order->city }} - {{ $order->state }}</p>
    <p>CEP: {{ $order->cep }}</p>

    <p>Se precisar de assistência, entre em contato conosco.</p>
    <p>Atenciosamente,<br>Sua Loja</p>
</body>
</html>
