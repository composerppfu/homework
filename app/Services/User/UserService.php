<?php

namespace App\Services\User;


use App\Models\GoogleAuth;
use App\Models\OtpCode;
use App\Models\PhoneAuth;
use App\Models\UserAuth;
use App\Models\Users;
use App\Services\BaseService;

class UserService extends BaseService
{
    public static function userProfile($userId){
        $userData = Users::query()
            ->where('user_id',$userId)
            ->first();
        $userAccount = UserAuth::query()
            ->where('id',$userId)
            ->first();
        $userData['phone_auth'] = PhoneAuth::query()
            ->where('user_id',$userId)
            ->value('phone');
        $userData['google_auth'] = GoogleAuth::query()
            ->where('user_id',$userId)
            ->value('google_email');
        $userAccount->password == null ? $userData['account'] = null : $userData['account'] = $userAccount->account;

        unset($userData['user_role']);
        return $userData;
    }

    public static function editUserProfile($requestData, $userId){
        $UserData = Users::query()
            ->find($userId);
        $attributes = array_intersect_key($requestData, array_flip($UserData->getFillable()));
        $UserData->setRawAttributes($attributes);
        $UserData->save();
        return $userId;
    }

    public static function phoneBind($requestData,$userId){
        OtpCode::query()
            ->where('phone',$requestData['phone'])
            ->where('code',$requestData['otp_code'])
            ->update([
                'is_use' => '1'
            ]);
        PhoneAuth::query()
            ->insert([
                'phone' => $requestData['phone'],
                'user_id' => $userId
            ]);
        return ['user_id' => $userId];
    }
    public static function accountBind($requestData,$userId){
        UserAuth::query()
            ->where('id',$userId)
            ->update([
                'account' => $requestData['account'],
                'password' => $requestData['password']
            ]);
        return ['user_id' => $userId];
    }
}
