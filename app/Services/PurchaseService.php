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
    public function purchase(array $data): Batch
    {
        return DB::transaction(function () use ($data) {
            $productId = collect($data['products'])->pluck('product_id')->unique();
            $products = Product::query()->with('category')->whereIn('id', $productId)->get()->keyBy('id');
            foreach ($data['products'] as $index => $item) {
                $product = $products->get($item['product_id']);
                if ($product === null) {
                    throw ValidationException::withMessages([
                        "products.$index.product_id" => 'Product not found.',
                    ]);
                }
                $category = $product->category;
                $visited = [];
                while ($category !== null && $category->parent_id !== null) {
                    if (isset($visited[$category->id])) {
                        throw ValidationException::withMessages([
                            "products.$index.product_id" => 'Cyclic category hierarchy.',
                        ]);
                    }

                    $visited[$category->id] = true;
                    $category = $category->parent;
                }
                if ($category === null ||  (int) $category->provider_id !== (int) $data['provider_id']) {
                    throw ValidationException::withMessages([
                        "products.$index.product_id" => 'Invalid provider.',
                    ]);
                }
            }
            $batch = Batch::query()->create([
                'provider_id' => $data['provider_id'],
            ]);
            foreach ($data['products'] as $item) {
                $batchLine = BatchLine::query()->create([
                    'batch_id' => $batch->id,
                    'product_id' => $item['product_id'],
                    'storage_id' => $item['storage_id'],
                    'quantity' => $item['quantity'],
                    'available_quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'sale_price' => $item['sale_price'],
                ]);
                StockMovement::query()->create([
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
