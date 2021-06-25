<?php


namespace App\Providers;


use App\Models\Users;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    /**
     * 只需設置角色常數並新增規則即可。
     */

    public function boot()
    {
        // 訪客規則
        Gate::define(Users::USER_ROLE_GUEST, function ($user) {
            return $user->user_role === Users::USER_ROLE_GUEST;
        });
        //使用者規則
        Gate::define(Users::USER_ROLE_Normal, function ($user) {
            return $user->user_role === Users::USER_ROLE_Normal;
        });
        //管理者規則
        Gate::define(Users::USER_ROLE_admin, function ($user) {
            return $user->user_role === Users::USER_ROLE_admin;
        });
    }
}
