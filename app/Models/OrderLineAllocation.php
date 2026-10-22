<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderLineAllocation extends Model {
    protected $fillable = ['order_line_id', 'batch_line_id', 'quantity', 'sale_price'];

    public function salesRefunds(): HasMany {
        return $this->hasMany(SalesRefund::class);
    }
}
