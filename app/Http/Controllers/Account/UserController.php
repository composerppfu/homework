<?php

namespace App\Http\Controllers\Account;

use App\Constants\ErrorCode;
use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\PhoneAuth;
use App\Models\UserAuth;
use App\Models\Users;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    /**
     * 個人資料
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|mixed
     */
    public function userProfile()
    {
        $userId = Auth::id();
        $checkUser = Users::query()
            ->where('user_id',$userId)
            ->first();
        if ($checkUser == null) {
            return response(['error' => ErrorCode::QUERY_ERROR],400);
        }
        $serviceData = UserService::userProfile($userId);
        return response(['data' => $serviceData]);
    }

    /**
     * 編輯個人資訊
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|mixed
     */
    public function editUserProfile(Request $request)
    {
        $requestData = $request::all();
        //check
        $checkRequest = Validator::make($requestData, [
            'name' => 'string|max:64',
            'phone' => 'digits_between:10,10|numeric|regex:/(09)[0-9]{8}/',
            'address' => 'string|max:255',
            'otp_code' => 'numeric'
        ]);
        if ($checkRequest->fails()){
            return response(['error' => ErrorCode::PARAM_INVALID_ERROR],400);
        }
        //unset more than value
        unset($requestData['user_role']);
        unset($requestData['create_time']);
        unset($requestData['user_id']);
        //service
        $serviceData = UserService::editUserProfile($requestData, Auth::id());
        return response(['data' => ['user_id' => $serviceData]]);
    }

    public function phoneBind(Request $request){
        $requestData = $request::all();
        //check
        $checkRequest = Validator::make($requestData, [
            'phone' => 'required|digits_between:10,10|numeric|regex:/(09)[0-9]{8}/',
            'otp_code' => 'required|numeric'
        ]);
        if ($checkRequest->fails()){
            return response(['error' => ErrorCode::PARAM_INVALID_ERROR],400);
        }
        //check the user have phoneAuth
        $checkHas = PhoneAuth::query()
            ->where('user_id',Auth::id())
            ->first();
        if ($checkHas != null){
            return response([ 'error' => ErrorCode::USER_RUN_ONCE ],400);
        }
        //check phone have bind other account
        $checkPhone = PhoneAuth::query()
            ->where('phone',$requestData['phone'])
            ->first();
        if ($checkPhone != null){
            return response([ 'error' => ErrorCode::REPEAT_PHONE_ERROR ],400);
        }
        //check otp code
        $otpCode = OtpCode::query()
            ->where('phone',$requestData['phone'])
            ->where('action','change')
            ->orderByDesc('create_time')
            ->first();
        if ($otpCode == null){
            return response([ 'error' => ErrorCode::OTP_NOT_EXIST_ERROR ],400);
        }
        if ($otpCode['code'] != $requestData['otp_code']){
            return response([ 'error' => ErrorCode::OTP_CODE_ERROR ],400);
        }
        if (date("Y-m-d H:i:s") > $otpCode['expire_time'] || $otpCode['is_use'] == '1'){
            return response([ 'error' => ErrorCode::OTP_INVALID_ERROR ],400);
        }
        //service
        $serviceData = UserService::phoneBind($requestData,Auth::id());
        return response(['data' => $serviceData]);
    }

    public function accountBind(Request $request){
        $requestData = $request::all();
        $checkRequest = Validator::make($requestData,[
            'account' => 'required|string',
            'password' => 'required|string|min:6',
            'check_password' => 'required|string|same:password',
        ]);
        if ($checkRequest->fails()){
            return response(['error' => ErrorCode::PARAM_INVALID_ERROR],400);
        }
        //check account
        $checkAccount = UserAuth::query()
            ->where('account',$requestData['account'])
            ->first();
        if ($checkAccount != null){
            return response(['error' => ErrorCode::ACCOUNT_HAS_USER],400);
        }
        $serviceData = UserService::accountBind($requestData,Auth::id());
        return response(['data' => $serviceData]);
    }
}
