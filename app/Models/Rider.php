<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    //
    protected $fillable = ['user_id','bike_id','start_lng','start_lat','end_lng','end_lat','start_at','end_at','money','is_pay'];
}
