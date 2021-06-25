<?php

namespace App\Http\Middleware;

use App\Constants\ErrorCode;
use App\Models\Users;
use Closure;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;
    protected $guard;
    protected $cache_key = "";//临时访问token cache的key

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     *
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        $this->guard = $this->auth->guard($guard);

        /**
         * 处理自动刷新token,及避免并发时，因发刷新token导致旧token无法使用的问题
         */

        $old_token       = $this->guard->getToken();//旧的token
        $this->cache_key = "JWT_TMP_TOKEN_" . $old_token;

        //校验Token有效性
        $has_tmp_token = Cache::has($this->cache_key);//是否有临时访问token

        if ($this->guard->guest() && $has_tmp_token!==true || $this->guard->check()!==true) {

            return response()->json([
                'error'=>ErrorCode::TOKEN_EXPIRED_ERROR
            ],401);
        }

        try {
            //自动刷新token(即将过期的token)
            $response = $this->refreshToken($has_tmp_token, $next($request), $request) ?? $next($request);

        }catch (\Throwable $exception) {
            return response()->json(ErrorCode::TOKEN_REFRESH_ERROR,401);
        }


        return $response;
    }


    /**
     * 自动刷新即将过期的token
     *
     * @param bool $has_tmp_token
     * @param $response
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function refreshToken($has_tmp_token, $response, $request)
    {

        //退出登录不用做自动刷新token处理
        if ($request->routeIs('api.v1.sign-out')) {
            return $response;
        }


        /**
         * 自动刷新token功能(token将要过期，且未设置过临时访问token,才去刷新token)
         */
        $payload = $this->guard->payload()->get();//token有效期信息

        if (!$has_tmp_token && ($payload['exp'] - time() < env('JWT_AUTO_REFRESH_TOKEN_TTL', 10))) {

            $newToken = $this->guard->refresh();//刷新token(生成一个新的token,并让旧token马上失效)

            Cache::put($this->cache_key, 1, env('JWT_AUTO_REFRESH_TOKEN_TTL', 10));//为防止并发时，其他请求携带旧token而被拒绝请求，将旧token设置为临时访问token

            $response->headers->set('Authorization', 'Bearer ' . $newToken);
        }

        return $response;

    }
}
