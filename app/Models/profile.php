<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class profile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'date_of_birth',
        'bio',
    ];

    function users()
    {
        return $this->belongsTo(User::class);
    }
}
