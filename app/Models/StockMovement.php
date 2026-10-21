<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model {
    protected $fillable = [
        'batch_line_id',
        'type',
        'quantity_change',
        'occurred_at',
    ];

    protected function casts(): array {
        return [
            'occurred_at' => 'datetime',
        ];
    }

    public function BatchLine(): BelongsTo {
        return $this->belongsTo(BatchLine::class);
    }
}
