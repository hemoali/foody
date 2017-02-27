<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'location', 'desc', 'link', 'phone_number', 'time', 'foursquare_id'];

    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
