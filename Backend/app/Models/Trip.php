<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $casts = [
        "origin" => "array",
        "destination" => "array",
        "driver_location" => "array"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
