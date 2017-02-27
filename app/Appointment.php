<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['time'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_appointments')->withPivot('status')->withTimestamps();
    }

}
