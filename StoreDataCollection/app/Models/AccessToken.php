<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessToken extends Model
{
    // Stops the auto fill for filling in the created_at and updated_at in the database.
    public $timestamps = false;

    // This is used to fill in, what there needs to be when creating
    protected $fillable =
    [
        'user_id',
        'token',
        'expire_at',
    ];

    // These are not received from the database
    protected $hidden =
    [
        'id',
        'user_id',
        'expire_at',
    ];

    // Relation to the user table, this is a 1 to many, many token to one user.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
