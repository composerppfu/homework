<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $table = 'otp_code';
    protected $primaryKey = 'id';
    protected $fillable = [
        'phone', 'code', 'action', 'is_use', 'expire_time', 'ip', 'create_time'
    ];
    public $timestamps = false;
}
