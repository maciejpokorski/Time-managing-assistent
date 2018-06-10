<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title','color'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function userId(){
        return $this->user()->get()->first()->id;
    }
}
