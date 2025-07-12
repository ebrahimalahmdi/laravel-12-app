<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'title',
        'descriotion',
        'priority',
        'user_id',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }


    function categories()
    {
        // return $this->hasMany(Category::class);
        return $this->belongsToMany(Category::class, 'category_task');
    }


    function favoriteByUser()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
