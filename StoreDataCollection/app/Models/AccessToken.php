<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessToken extends Model
{
    public $timestamps = false;
    protected $fillable =
    [
        'user_id',
        'token',
        'expire_at',
    ];

    protected $hidden =
    [
        'id',
        'user_id',
        'expire_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
