<?php

namespace App\Http\Controllers\Account;

use App\Constants\ErrorCode;
use App\Http\Controllers\Controller;
use App\Models\UserAuth;
use App\Models\Users;
use App\Services\Account\AccountService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * 確認帳號
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function accountCheck(Request $request){
        $requestData = $request::all();
        $checkRequest = Validator::make($requestData,[
            'account' => 'required|string',
        ]);
        if ($checkRequest->fails()){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        $checkAccount = UserAuth::query()
            ->where('account',$requestData['account'])
            ->first();
        if($checkAccount == null){
            return response(['data' => (object)['has_account' => false]]);
        }else{
            return response(['data' => (object)['has_account' => true]]);
        }
    }
    /**
     * 帳號註冊
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function accountRegister(Request $request){
        $requestData = $request::all();
        $checkRequest = Validator::make($requestData,[
            'account' => 'required|string',
            'password' => 'required|string|min:6',
            'check_password' => 'required|string|same:password',
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'digits_between:10,10|numeric|regex:/(09)[0-9]{8}/',
        ]);
        if ($checkRequest->fails()){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        $checkHasAccount = UserAuth::query()
            ->where('account',$requestData['account'])
            ->first();
        if ($checkHasAccount != null){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        $serviceData = AccountService::AccountRegister($requestData);
        return response(['data' => $serviceData]);
    }
    /**
     * 帳號登入
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function accountLogin(Request $request){
        $requestData = $request::all();
        $checkRequest = Validator::make($requestData,[
            'account' => 'required|string',
            'password' => 'required|string|min:6'
        ]);
        if ($checkRequest->fails()){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        $checkAccount = UserAuth::query()
            ->where('account',$requestData['account'])
            ->first();
        if ($checkAccount == null || $checkAccount->password != $requestData['password']){
            return response([ 'error' => ErrorCode::LOGIN_ERROR ],400);
        }
        $user = Users::query()
            ->where('user_id',$checkAccount['id'])
            ->first();
        $token = Auth::login($user);
        $returnData['user_id'] = $user->user_id;
        $returnData['user_role'] = $user->user_role;
        $returnData['token'] = 'Bearer ' . $token;
        return response(['data' => $returnData]);
    }
}
