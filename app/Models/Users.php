<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const USER_ROLE_Normal = 'normal';//普通使用者
    const USER_ROLE_GUEST = 'guest';//訪客
    const USER_ROLE_admin = 'admin';//管理者

    protected $table = 'user_table';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_id','name', 'phone', 'address', 'user_role', 'create_time'
    ];
    public $timestamps = false;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
//        return ['vge'=>DB::table($this->table)->where('id',$this->getKey())->value('village_id')];
        return [];
    }

}
