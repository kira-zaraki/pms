<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = ['room_id', 'image'];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    protected static function booted()
    {
        static::deleting(function ($gallery) {
            Storage::disk('local')->delete($gallery->image);
        });
    }
}
