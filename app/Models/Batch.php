<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model {
    protected $fillable = [
        'provider_id',
    ];

    public function batchLines(): HasMany {
        return $this->hasMany(BatchLine::class);
    }

    public function provider(): BelongsTo {
        return $this->belongsTo(Provider::class);
    }
}
