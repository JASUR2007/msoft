<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRefund extends Model {
    protected $fillable = [
        'batch_line_id',
        'quantity',
        'amount',
    ];

    public function batchLine(): BelongsTo {
        return $this->belongsTo(BatchLine::class);
    }
}
