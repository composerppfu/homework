<?php

namespace App\Http\Controllers\Account;

use App\Constants\ErrorCode;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Models\UserAuth;
use App\Models\Users;
use App\Services\Account\AccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    //訪客
    public function guestLogin(){
        $serviceData = AccountService::GuestLogin();
        return response(['data' => $serviceData]);
    }
    //登出
    public function signOut(){
        $userId = Auth::user()['user_id'];
        if (Auth::user()['user_role'] == 'guest'){
            Users::query()
                ->where('user_id',$userId)
                ->delete();
            UserAuth::query()
                ->where('id',$userId)
                ->delete();
        }
        Auth::logout(true);
        return response(['data' => (object)[]]);
    }
}
