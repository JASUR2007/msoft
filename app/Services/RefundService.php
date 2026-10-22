<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\BatchLine;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\OrderLineAllocation;
use App\Models\PurchaseRefund;
use App\Models\SalesRefund;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundService {
    public function refund(array $data, Batch $batch): PurchaseRefund {
        return DB::transaction(function () use ($batch, $data) {
            $batchLine = BatchLine::query()->lockForUpdate()->findOrFail($data['batch_line_id']);
            if ((int) $batchLine->batch_id !== (int) $batch->id) {
                throw ValidationException::withMessages([
                    'batch_line_id' => 'Batchline not equal to batch.',
                ]);
            }
            if ($batchLine->available_quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Not enough quantity',
                ]);
            }
            $batchLine->decrement('available_quantity', $data['quantity']);
            $refund = PurchaseRefund::query()->create([
                'batch_line_id' => $batchLine->id,
                'quantity' => $data['quantity'],
                'amount' => $batchLine->purchase_price * $data['quantity'],
            ]);
            $batchLine->stockMovements()->create([
                'type' => 'purchase_return',
                'quantity_change' => -$data['quantity'],
                'occurred_at' => now(),
            ]);

            return $refund;
        });
    }

    public function refundSale(array $data, Order $order): SalesRefund {
        return DB::transaction(function () use ($data, $order) {
            $allocation = OrderLineAllocation::query()->lockForUpdate()
                ->findOrFail($data['order_line_allocation_id']);
            $line = OrderLine::query()->findOrFail($allocation->order_line_id);
            if ((int) $line->order_id !== (int) $order->id) {
                throw ValidationException::withMessages([
                    'order_line_allocation_id' => 'Allocation is not connected to order.',
                ]);
            }

            $returned = $allocation->salesRefunds()->sum('quantity');
            if ($data['quantity'] > $allocation->quantity - $returned) {
                throw ValidationException::withMessages([
                    'quantity' => 'Quantity exceeds the  quantity.',
                ]);
            }

            $batchLine = BatchLine::query()->lockForUpdate()->findOrFail($allocation->batch_line_id);
            $refund = SalesRefund::query()->create([
                'order_line_allocation_id' => $allocation->id,
                'quantity' => $data['quantity'],
                'amount' => round($allocation->sale_price * $data['quantity'], 2),
            ]);
            $batchLine->increment('available_quantity', $data['quantity']);
            $batchLine->stockMovements()->create([
                'type' => 'sale_return', 'quantity_change' => $data['quantity'], 'occurred_at' => now(),
            ]);

            return $refund;
        }, 3);
    }
}
