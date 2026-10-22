<?php

namespace App\Http\Controllers;

use App\Models\BatchLine;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller {
    public function index(): JsonResponse {
        $firstPrice = BatchLine::query()->select('sale_price')
            ->whereColumn('product_id', 'products.id')->where('available_quantity', '>', 0)
            ->orderBy('created_at')->orderBy('id')->limit(1);

        $products = Product::query()->with('category:id,name')
            ->addSelect(['price' => $firstPrice])
            ->whereHas('batchLines',
                function ($query) {
                    return $query->where('available_quantity', '>', 0);
                })
            ->withSum('batchLines as qty', 'available_quantity')
            ->get()
            ->map(function ($product) {
                return ['id' => $product->id,
                    'name' => $product->name,
                    'category_name' => $product->category->name,
                    'price' => $product->price,
                    'qty' => (int) $product->qty,
                ];
            });
        return response()->json($products);
    }
}
