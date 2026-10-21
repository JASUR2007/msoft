<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    protected $fillable = [
        'client_id',
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function orderLines(): HasMany {
        return $this->hasMany(OrderLine::class);
    }
}
