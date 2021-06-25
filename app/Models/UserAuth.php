<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    protected $table = 'user_auth';
    protected $primaryKey = 'id';
    protected $fillable = [
        'account', 'password'
    ];
    public $timestamps = false;
}
