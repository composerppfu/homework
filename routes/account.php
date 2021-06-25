<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * @var \Laravel\Lumen\Routing\Router $router
 */
//帳號登入
$router->group(['namespace'=>'Account','prefix' => 'api/v1/account'], function () use ($router) {
    $router->post('check','AccountController@accountCheck');
    $router->post('register','AccountController@accountRegister');
    $router->post('login','AccountController@accountLogin');
});
//手機登入
$router->group(['namespace'=>'Account','prefix' => 'api/v1/phone'], function () use ($router) {
    $router->post('send-otp','SmsController@otpSend');
    $router->post('check','PhoneController@phoneCheck');
    $router->post('register','PhoneController@phoneRegister');
    $router->post('login','PhoneController@phoneLogin');
});
//訪客登入
$router->group(['namespace'=>'Account','prefix' => 'api/v1/guest'], function () use ($router) {
    $router->post('/login','LoginController@guestLogin');
});
//登出
$router->group(['namespace'=>'Account','prefix' => 'api/v1','as' => 'api.v1' ,'middleware' => ['auth']], function () use ($router) {
    $router->post('/sign-out',['as' => 'sign-out', 'uses' => 'LoginController@signOut']);
});

//guest login
//$router->group(['namespace'=>'Account','prefix' => 'api/v1/phone'], function () use ($router) {
//    $router->post('send-otp','SmsController@otpSend');
//    $router->post('check','PhoneController@phoneCheck');
//    $router->post('register','PhoneController@phoneRegister');
//    $router->post('login','PhoneController@phoneLogin');
//});
//$router->group(['namespace'=>'Account','prefix' => 'api/v1/my','middleware' => ['auth']], function () use ($router) {
//    $router->group(['middleware'=>'role:resident,chief'],function () use ($router){
//        $router->get('profile', 'UserController@userProfile');
//        $router->patch('profile', 'UserController@editUserProfile');
//    });
//});



/*
    路由规则设置

    HTTP方法	    URI	                    动作	            路由名称
    GET	        /photos	                index	        photos.index
    POST	    /photos	                store	        photos.store
    GET	        /photos/{photo}	        show	        photos.show
    PUT     	/photos/{photo}	        update	        photos.update
    DELETE	    /photos/{photo}	        destroy	        photos.delete
 */
//個人資料
//$router->group(['namespace'=>'Account','prefix' => 'api/v1/my','middleware' => ['auth']], function () use ($router) {
//
//
//    $router->group(['middleware'=>'role:resident,chief'],function () use ($router){
//
//        /**
//         * 權限: 里民、里長
//         *
//         * 個人資料      userProfile
//         * 個人資料/編輯 editUserProfile
//         */
//        $router->get('profile', 'UserController@userProfile');
//        $router->patch('profile', 'UserController@editUserProfile');
//    });
//});
//
//
//$router->group(['namespace' => 'Account', 'prefix' => 'api/v1/auth', 'as' => 'api.v1.account'], function () use ($router) {
//
//    /**
//     * 帳號/新增訪客     guestLogin
//     * 帳號/登入        login
//     * 帳號/註冊        register
//     * 確認手機註冊狀態  phoneCheck
//     * 帳號/更新token   refreshToken
//     */
//    $router->post('guest-login','LoginController@guestLogin');
//    $router->post('login', 'LoginController@login');
//    $router->post('register', 'LoginController@register');
//    $router->post('check-phone','LoginController@phoneCheck');
//    $router->post('refresh', [
////        'middleware' => 'jwt.refresh',
//        'as'         => 'login.refresh-token',
//        'uses'       => 'LoginController@refreshToken'
//    ]);
//
//    $router->group(['middleware' => 'auth'], function () use ($router) {
//
//        /**
//         * 需使用者驗證登錄
//         */
//
//        $router->group(['middleware'=>'role:resident,chief'],function () use ($router){
//
//            /**
//             * 權限: 里民、里長
//             *
//             * 帳號/登出 logout
//             */
//            $router->post('logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);
//        });
//
//        $router->group(['middleware'=>'role:guest,resident,chief'],function () use ($router){
//
//            /**
//             * 權限: 訪客、里民、里長
//             *
//             * 切換村里 changeVillage
//             */
//            $router->post('change-village', ['as' => 'changeVillage', 'uses' => 'LoginController@changeVillage']);
//        });
//
//    });
//});

