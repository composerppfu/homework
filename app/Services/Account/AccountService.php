<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020/2/3
 * Time: 4:11 PM
 */

namespace App\Services\Account;

use App\Models\OtpCode;
use App\Models\PhoneAuth;
use App\Models\UserAuth;
use App\Models\Users;
use App\Services\BaseService;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Auth;


class AccountService extends BaseService
{
    public static function AccountRegister($requestData){
        //creat to user_auth
        $user = UserAuth::query()->create($requestData);
        //add data to requestData for user_table
        $requestData['user_id'] = $user->id;
        $requestData['create_time'] = date("Y-m-d H:i:s");
        $requestData['user_role'] = 'normal';
        //create data to user_table
        $userData = Users::query()->create($requestData);
        $userData['user_id'] = $user->id;
        $data['user_id'] = $user->id;
        $data['user_role'] = $userData->user_role;
        $data['token'] = 'Bearer ' . Auth::login($userData);
        return $data;
    }

    public static function PhoneRegister($requestData){
        $fakeAccount['account'] = Uuid::uuid();
        $checkFake = UserAuth::query()
            ->where('account',$fakeAccount['account'])
            ->first();
        if ($checkFake != null){
            $fakeAccount['account'] = Uuid::uuid();
        }
        $user = UserAuth::query()
            ->create($fakeAccount);
        $requestData['user_id'] = $user->id;
        $requestData['create_time'] = date("Y-m-d H:i:s");
        $requestData['user_role'] = 'normal';
        $userData = Users::query()
            ->create($requestData);
        $userData['user_id'] = $user->id;
        PhoneAuth::query()
            ->create($requestData);
        OtpCode::query()
            ->where('phone',$requestData['phone'])
            ->where('code',$requestData['otp_code'])
            ->update([
                'is_use' => '1'
            ]);
        $data['user_id'] = $user->id;;
        $data['user_role'] = $userData->user_role;
        $data['token'] = 'Bearer ' . Auth::login($userData);
        return $data;
    }

    public static function GuestLogin(){
        $fakeAccount['account'] = Uuid::uuid();
        $checkFake = UserAuth::query()
            ->where('account',$fakeAccount['account'])
            ->first();
        if ($checkFake != null){
            $fakeAccount['account'] = Uuid::uuid();
        }
        $userId = UserAuth::query()
            ->create($fakeAccount);
        $requestData['user_id'] = $userId->id;
        $requestData['name'] = '訪客';
        $requestData['user_role'] = 'guest';
        $requestData['create_time'] = date("Y-m-d H:i:s");
        $userData = Users::query()
            ->create($requestData);
        $userData['user_id'] = $userId->id;
        //
        $data['user_id'] = $userId->id;
        $data['user_role'] = $userData->user_role;
        $data['token'] = 'Bearer ' . Auth::login($userData);
        //
        return $data;
    }
}
