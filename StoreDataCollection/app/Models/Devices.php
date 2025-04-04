<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Devices extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'uuid',
    ];

    protected $hidden = [];

    // this is the many to many relation.
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Groups::class);
    }

    // 1 to many, this is the one where 1 device has many data.
    public function data(): HasMany
    {
        return $this->hasMany(Data::class, 'device_id');
    }
}
