<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    protected $fillable = ['title', 'budget', 'cpm'];

    public function stats(): HasMany
    {
        return $this->hasMany(AdStat::class);
    }
}
