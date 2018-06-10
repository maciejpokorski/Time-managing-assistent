<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title','start_date','end_date', 'category_id'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function userId()
    {
        return $this->category()->get()->first()->user_id;
    }
}
