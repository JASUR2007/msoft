<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLineAllocation extends Model {
    protected $fillable = ['order_line_id', 'batch_line_id', 'quantity', 'sale_price'];
}
