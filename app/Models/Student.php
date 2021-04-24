<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

	protected $fillable = ['name','grade','photo','date_of_birth','address','city_id','country_id','state_id'];    

	public  function city()
    {
        return $this->belongsTo(City::class,'city_id','id');
    }

    public  function state()
    {
        return $this->belongsTo(State::class,'state_id','id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
