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
}
