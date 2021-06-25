<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    private $headers;
    private $allow_origin;

    public function handle(Request $request, \Closure $next)
    {

        $this->headers = [
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, PATCH',
            'Cache-Control'                    => 'no-cache',
            'Access-Control-Allow-Headers'     => $request->header('Access-Control-Request-Headers',['Authorization','Content-Type','X-Requested-With']),
            'Access-Control-Expose-Headers'    => $request->header('Access-Control-Expose-Headers',['Authorization']),
            'Access-Control-Allow-Credentials' => 'true',//允许客户端发送cookie
            'Access-Control-Max-Age'           => 1728000 //该字段可选，用来指定本次预检请求的有效期，在此期间，不用发出另一条预检请求。
        ];
        $origin        = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
//        Log::debug("origin:" . $origin);
        $this->allow_origin = [
            '*',
        ];

        //如果origin不在允许列表内，直接返回403
        if ((!in_array('*', $this->allow_origin) && !in_array($origin, $this->allow_origin) && !empty($origin)))
            return response('Forbidden', 403);

        //如果是复杂请求，先返回一个200，并allow该origin
        if ($request->isMethod('options'))
            return $this->setCorsHeaders(response('OK', 200), $origin);
        //如果是简单请求或者非跨域请求，则照常设置header
        $response       = $next($request);
        $methodVariable = array($response, 'header');
        //这个判断是因为在开启session全局中间件之后，频繁的报header方法不存在，所以加上这个判断，存在header方法时才进行header的设置
        if (is_callable($methodVariable, false, $callable_name)) {
            return $this->setCorsHeaders($response, $origin);
        }
        return $response;
    }

    /**
     * @param Response $response
     *
     * @param $origin
     *
     * @return mixed
     */
    public function setCorsHeaders($response, $origin)
    {
//        Log::debug("setCorsHeaders start",$response->headers->all());
        foreach ($this->headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        if ($origin) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        } else {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }

//        Log::debug("setCorsHeaders end",$response->headers->all());
        return $response;
    }
}
