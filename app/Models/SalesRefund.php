<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesRefund extends Model {
    protected $fillable = ['order_line_allocation_id', 'quantity', 'amount'];
}
