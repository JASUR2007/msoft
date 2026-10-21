<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $fillable = [
        'provider_id',
    ];
    public function batchLine(): BelongsTo {
      return $this->belongsTo(BatchLine::class);
    }
    public function providers(): HasMany {
        return $this->hasMany(Provider::class);
    }
}
