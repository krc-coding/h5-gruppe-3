<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Data extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'people',
        'products_pr_person',
        'total_value',
        'product_categories',
        'packages_received',
        'packages_delivered',
        'device_id',
        'data_recorded_at',
    ];

    protected $hidden = [];

    public function devices(): BelongsTo
    {
        return $this->belongsTo(Devices::class);
    }
}
