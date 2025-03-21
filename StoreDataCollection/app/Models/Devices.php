<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devices extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'uuid',
    ];

    protected $hidden = [];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Groups::class);
    }

    public function data(): HasMany
    {
        return $this->hasMany(Data::class);
    }
}
