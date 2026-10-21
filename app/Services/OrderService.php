<?php

namespace App\Services;

use App\Models\BatchLine;
use App\Models\Order;
use App\Models\OrderLineAllocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService {
    public function create(array $data): Order {
        return DB::transaction(function () use ($data) {
            $order = Order::query()->create(['client_id' => $data['client_id']]);
            $items = collect($data['products'])->sortBy('id');
            foreach ($items as $index => $item) {
                $stock = BatchLine::query()->where('product_id', $item['id'])->where('available_quantity', '>', 0)
                    ->orderBy('created_at')->orderBy('id')->lockForUpdate()->get();
                if ($stock->sum('available_quantity') < $item['qty']) {
                    throw ValidationException::withMessages([
                        "products.$index.qty" => 'Not enough available stock.',
                    ]);
                }
                $line = $order->orderLines()->create([
                    'product_id' => $item['id'], 'quantity' => $item['qty'],
                ]);
                $remaining = $item['qty'];
                foreach ($stock as $batchLine) {
                    $quantity = min($remaining, $batchLine->available_quantity);
                    OrderLineAllocation::query()->create([
                        'order_line_id' => $line->id,
                        'batch_line_id' => $batchLine->id,
                        'quantity' => $quantity,
                        'sale_price' => $batchLine->sale_price,
                    ]);
                    $batchLine->decrement('available_quantity', $quantity);
                    $batchLine->stockMovements()->create([
                        'type' => 'sale', 'quantity_change' => -$quantity, 'occurred_at' => now(),
                    ]);
                    $remaining -= $quantity;
                    if ($remaining === 0) {
                        break;
                    }
                }
            }

            return $order->load('orderLines.orderLineAllocations');
        }, 3);
    }
}
