<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PhoneAuth extends Model
{
    protected $table = 'phone_auth';
    protected $fillable = [
        'user_id','phone'
    ];
    public $timestamps = false;
}
