<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title','color', 'user_id'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
