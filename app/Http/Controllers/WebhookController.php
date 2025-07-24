<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
     public function handle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'status' => 'required|string|in:pending,approved,canceled,rejected,shipped,delivered',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'details' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $order = Order::findOrFail($validated['order_id']);

        if ($order->status === $validated['status']) {
            return response()->json([
                'message' => 'Status do pedido inalterado.',
                'order_id' => $order->id,
                'status' => $order->status,
            ], 200);
        }

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Status do pedido atualizado com sucesso.',
            'order_id' => $order->id,
            'status' => $order->status,
        ], 200);
    }
}
