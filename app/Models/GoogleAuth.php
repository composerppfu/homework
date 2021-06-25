<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GoogleAuth extends Model
{
    protected $table = 'google_auth';
    protected $fillable = [
        'user_id','google_user_id'
    ];
    public $timestamps = false;
}
