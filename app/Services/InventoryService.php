<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InventoryService {
    public function stock(string $date, ?int $storageId = null): Collection {
        return DB::table('stock_movements as movements')
            ->join('batch_lines as lines', 'lines.id', '=', 'movements.batch_line_id')
            ->join('products', 'products.id', '=', 'lines.product_id')
            ->where('movements.occurred_at', '<', CarbonImmutable::parse($date)->addDay()->startOfDay())
            ->when($storageId, fn ($query) => $query->where('lines.storage_id', $storageId))
            ->select('lines.storage_id', 'products.id', 'products.name')
            ->selectRaw('SUM(movements.quantity_change) as qty')
            ->groupBy('lines.storage_id', 'products.id', 'products.name')
            ->orderBy('lines.storage_id')->orderBy('products.id')->get()
            ->map(function ($row) {
                $row->qty = (int) $row->qty;

                return $row;
            });
    }

    public function profits(): Collection {
        $returns = DB::table('sales_refunds')->select('order_line_allocation_id')
            ->selectRaw('SUM(quantity) as qty')->groupBy('order_line_allocation_id');

        $sales = DB::table('order_line_allocations as allocations')
            ->leftJoinSub($returns, 'returns', 'returns.order_line_allocation_id', '=', 'allocations.id')
            ->select('allocations.batch_line_id')
            ->selectRaw('SUM(allocations.quantity - COALESCE(returns.qty, 0)) as sold_qty')
            ->selectRaw('SUM((allocations.quantity - COALESCE(returns.qty, 0)) * allocations.sale_price) as revenue')
            ->groupBy('allocations.batch_line_id');

        return DB::table('batches')
            ->leftJoin('batch_lines as lines', 'lines.batch_id', '=', 'batches.id')
            ->leftJoinSub($sales, 'sales', 'sales.batch_line_id', '=', 'lines.id')
            ->select('batches.id')
            ->selectRaw('COALESCE(SUM(sales.revenue), 0) as revenue')
            ->selectRaw('COALESCE(SUM(sales.sold_qty * lines.purchase_price), 0) as cost')
            ->selectRaw('COALESCE(SUM(sales.revenue - sales.sold_qty * lines.purchase_price), 0) as profit')
            ->groupBy('batches.id')->orderBy('batches.id')->get();
    }
}
