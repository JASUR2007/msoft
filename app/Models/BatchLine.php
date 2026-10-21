<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchLine extends Model
{
    protected $fillable = [
      'batch_id',
      'product_id',
      'storage_id',
      'quantity',
      'available_quantity',
      'purchase_price',
      'sale_price'
    ];
    public function stockMovements(): HasMany {
        return $this->hasMany(StockMovement::class);
    }
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
    public function batch(): BelongsTo {
        return $this->belongsTo(Batch::class);
    }

    public function orderLineAllocations(): HasMany {
        return $this->hasMany(OrderLineAllocation::class);
    }
    public function purchaseRefunds(): HasMany {
        return $this->hasMany(PurchaseRefund::class);
    }
    public function  Storage(): BelongsTo {
        return $this->belongsTo(Storage::class);
    }
}
