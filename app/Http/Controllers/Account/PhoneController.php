<?php

namespace App\Http\Controllers\Account;

use App\Constants\ErrorCode;
use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\PhoneAuth;
use App\Models\UserAuth;
use App\Models\Users;
use App\Services\Account\AccountService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PhoneController extends Controller
{
    /**
     * 確認電話
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function phoneCheck(Request $request){
        $requestData = $request::all();
        $checkRequest = Validator::make($requestData,[
            'phone' => 'required|digits_between:10,10|numeric|regex:/(09)[0-9]{8}/'
        ]);
        if ($checkRequest->fails()){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        $checkAccount = PhoneAuth::query()
            ->where('phone',$requestData['phone'])
            ->first();
        if($checkAccount == null){
            return response(['data' => (object)['has_phone' => false]]);
        }else{
            return response(['data' => (object)['has_phone' => true]]);
        }
    }
    /**
     * 電話註冊
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function phoneRegister(Request $request){
        $requestData = $request::all();
        $checkRequest = Validator::make($requestData,[
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|digits_between:10,10|numeric|regex:/(09)[0-9]{8}/',
            'otp_code' => 'required|numeric'
        ]);
        if ($checkRequest->fails()){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        $checkHasPhone = PhoneAuth::query()
            ->where('phone',$requestData['phone'])
            ->first();
        if ($checkHasPhone != null){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        //OTP code Check
        $thePhoneOtpCode = OtpCode::query()
            ->where('phone',$requestData['phone'])
            ->where('action','register')
            ->orderByDesc('create_time')
            ->first();
        if ($thePhoneOtpCode == null){
            return response([ 'error' => ErrorCode::OTP_NOT_EXIST_ERROR ],400);
        }
        if ($thePhoneOtpCode['code'] != $requestData['otp_code']){
            return response([ 'error' => ErrorCode::OTP_CODE_ERROR ],400);
        }
        if(date("Y-m-d H:i:s") > $thePhoneOtpCode['expire_time'] || $thePhoneOtpCode['is_use'] == 1){
            return response([ 'error' => ErrorCode::OTP_INVALID_ERROR ],400);
        }
        $serviceData = AccountService::PhoneRegister($requestData);
        return response(['data' => $serviceData]);
    }
    /**
     * 電話登入
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function phoneLogin(Request $request){
        $requestData = $request::all();
        $checkRequest = Validator::make($requestData,[
            'phone' => 'required|digits_between:10,10|numeric|regex:/(09)[0-9]{8}/',
            'otp_code' => 'required|numeric'
        ]);
        if ($checkRequest->fails()){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        $checkPhone = PhoneAuth::query()
            ->where('phone',$requestData['phone'])
            ->first();
        $otpCode = OtpCode::query()
            ->where('phone',$requestData['phone'])
            ->where('action','login')
            ->orderByDesc('create_time')
            ->first();
        if ($checkPhone == null){
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        if ($otpCode == null){
            return response([ 'error' => ErrorCode::OTP_NOT_EXIST_ERROR ],400);
        }
        if ($otpCode['code'] != $requestData['otp_code']){
            return response([ 'error' => ErrorCode::OTP_CODE_ERROR ],400);
        }
        if (date("Y-m-d H:i:s") > $otpCode['expire_time'] || $otpCode['is_use'] == '1'){
            return response([ 'error' => ErrorCode::OTP_INVALID_ERROR ],400);
        }
        $user = Users::query()
            ->where('user_id',$checkPhone['user_id'])
            ->first();
        $token = Auth::login($user);
        $returnData['user_id'] = $user->user_id;
        $returnData['user_role'] = $user->user_role;
        $returnData['token'] = 'Bearer ' . $token;
        return response(['data' => $returnData]);
    }
}
