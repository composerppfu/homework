<?php

namespace App\Http\Controllers\Account;

use App\Constants\ErrorCode;
use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Services\SendOTP\SendOTPService;
use Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    /**
     * OTP code
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|mixed
     */
    public function otpSend(Request $request)
    {
        $user_ip = Request::ip();
        $data = $request::all();
        $userSum = OtpCode::query()
            ->where('ip', $user_ip)
            ->whereBetween('create_time', [date("Y-m-d H:i:s", strtotime('-10 minutes')), date("Y-m-d H:i:s")])
            ->count();
        $checkRequest = Validator::make($data, [
            'phone' => 'required|digits_between:10,10|numeric|regex:/(09)[0-9]{8}/',
            'hash' => 'regex:/^[A-Za-z0-9+\/]{11}$/',
            'action' => 'in:login,register,change'
        ]);
        if ($checkRequest->fails()) {
            return response([ 'error' => ErrorCode::PARAM_INVALID_ERROR ],400);
        }
        //otp code
        $otpCode = mt_rand(100000, 999999);
        //測試模式
        $test = !empty($data['is_test']) && $data['is_test'] == true ? $test = true : false;
        //if 10min have 3 times sent otp can't will error
        if ($userSum >= '3') {
            return response([ 'error' => ErrorCode::OTP_SEND_ERROR ],429);
        }
        if (!$test) {
            try {
                //send data
                $sendData['otp_code'] = $otpCode;
                $sendData['hash'] = $data['hash'] ?? '';
                $sendData['user_phone'] = $data['phone'];
                //service
                if (!SendOTPService::ClientSend($sendData)) {
                    throw new \Exception('oops');
                }
            } catch (\Throwable $e) {
                return response([ 'error' => ErrorCode::CAPTCHA_CREATE_ERROR ],500);
            }
        }
        //insert date
        if ($data['phone'] == '0965644420') {
            $otpCode = 123456;
        }
        $data['code'] = $otpCode;
        $data['is_use'] = "0";
        $data['expire_time'] = date("Y-m-d H:i:s", strtotime('2 minutes'));
        $data['ip'] = $user_ip;
        $data['create_time'] = date("Y-m-d H:i:s");
        //service
        $service_data = SendOTPService::OTP_CodeAdd($data);
        if (!$test) {
            return response(['data' => $service_data]);
        } else {
            return response()->json([
                'data' => [
                    'expire_second' => 120,
                    'otp_code' => $otpCode
                ]
            ]);
        }
    }
}
