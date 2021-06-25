<?php

namespace App\Services\SendOTP;

use App\Models\OtpCode;
use App\Services\BaseService;
use GuzzleHttp\Client;


class SendOTPService extends BaseService
{
    public static function OTP_CodeAdd($data){
        OtpCode::query()->create($data);
        return ['expire_second' => '120'];
    }
    public static function ClientSend($senddata){
        $smsHost = "api.every8d.com";
        $sendSMSUrl = "http://".$smsHost."/API21/HTTP/sendSMS.ashx";
        $postDataString = "UID=" . env('OTP_code_account',null);
        $postDataString .= "&PWD=" . env('OTP_code_pwd',null);
        $postDataString .= "&SB=" . '';
        if(empty($senddata['hash'])) {
            $postDataString .= "&MSG=" .'限當次有效，請在時效內輸入驗證碼:'.$senddata['otp_code'];
        }
        else {
            $postDataString .= "&MSG=" . '限當次有效，請在時效內輸入驗證碼:'.$senddata['otp_code'].'-'.$senddata['hash'];
        }
        $postDataString .= "&DEST=" . $senddata['user_phone'];
        $postDataString .= "&ST=" . '';
        $client = new Client();
        $new = $client->post($sendSMSUrl.'?'.$postDataString);
        $new = $new->getBody()->getContents();
        $cliback = explode(" ",$new)[0];
        $nomoney = mb_split("\,",$cliback)[0];
        if($cliback < 0 || $nomoney < 0 ) {
            return false;
        }
        else{
            return true;
        }

    }
}
