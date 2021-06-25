<?php

namespace App\Http\Middleware;

use App\Constants\ErrorCode;
use Closure;
use Illuminate\Support\Facades\Gate;

class RoleMiddleware
{
    /**
     * 角色權限
     * @param $request
     * @param Closure $next
     * @param null $role1
     * @param null $role2
     * @param null $role3
     * @param null $role4
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next, $role1 = null, $role2 = null, $role3 = null, $role4 = null)
    {
        $roles = [$role1, $role2, $role3, $role4];

        //驗證使用者角色權限
        foreach ($roles as $role) {
            if (!empty($role)) {
                $result = Gate::allows($role);
                if ($result == true) {
                    break;
                }
            }
        }

        //驗證結果
        if ($result == false) {
            return response()->json([
                "error"=>ErrorCode::PERMISSION_INSUFFICIENT
            ], 403);
        }

        return $next($request);
    }
}
