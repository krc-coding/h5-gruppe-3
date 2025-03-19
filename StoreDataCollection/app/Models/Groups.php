<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Groups extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'user_id',
    ];

    protected $hidden = [];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Devices::class);
    }
}
