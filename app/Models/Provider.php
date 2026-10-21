<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    protected $fillable = [
        'name'
    ];
    public function category(): HasMany {
        return $this->hasMany(Category::class);
    }
    public function batch(): HasMany {
        return $this->hasMany(Batch::class);
    }
}
