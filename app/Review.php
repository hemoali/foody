<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['text', 'rate'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }
}
