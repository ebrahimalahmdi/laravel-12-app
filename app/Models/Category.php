<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    function tasks()
    {
        return $this->belongsToMany(Task::class, 'category_task');
        // return $this->hasMany(Task::class);
    }
}
