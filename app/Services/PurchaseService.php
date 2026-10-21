<?php

namespace App\Services;
use App\Models\Batch;
use App\Models\BatchLine;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class PurchaseService
{
    public function purchase(array $data): Batch {
        return DB::transaction(function () use ($data){
            foreach ($data['products'] as $item) {
                $product = Product::with('category')->findOrFail($item['product_id']);
                $category = $product->category;
               while ($category !== null && $category->parent_id !== null) {
                   $category = $category->parent;
               }
                if ($category === null ||
                    (int) $category->provider_id !== (int) $data['provider_id']) {
                    throw ValidationException::withMessages([
                        'products' => 'Product does not belong to provider.',
                    ]);
                }
            }
            $batch = Batch::create([
                'provider_id' => $data['provider_id']
            ]);
            foreach ($data['products'] as $item) {
                $batchLine = BatchLine::create([
                    'batch_id' => $batch->id,
                    'product_id' => $item['product_id'],
                    'storage_id' => $item['storage_id'],
                    'quantity' => $item['quantity'],
                    'available_quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'sale_price' => $item['sale_price'],
                ]);
                StockMovement::create([
                    'batch_line_id' => $batchLine->id,
                    'type' => 'purchase',
                    'quantity_change' => $item['quantity'],
                    'occurred_at' => now(),
                ]);
            }
            return $batch;
        });
    }
}
