<?php
/**
 * Created by PhpStorm.
 * User: wtone
 * Date: 2019/3/26
 * Time: 下午8:49
 */

namespace App\Constants;

/**
 * 錯誤代碼定義類
 * Class ErrorCode
 *
 * @package App\Consts
 */
class ErrorCode
{
    /**
     * 獲取錯誤碼
     *
     * @param array  $error_code 錯誤碼（以下的錯誤碼中選取）
     * @param string $message
     *
     * @return array
     */
    public static function error($error_code, $message = '')
    {
        if (trim($message) != '') {
            $error_code['message'] .= ':' . $message;
        }
        return $error_code;
    }


    /**
     * 錯誤碼定義避開100 ~ 511 的HTTP錯誤碼定義
     */
    //內置錯誤碼
    const  NO_ERROR               = ['code' => 0, 'message' => '請求成功','description'=>'請求已成功執行'];
    const  ERROR                  = ['code' => 1, 'message' => '請求失敗','description'=>'請求結果失敗或被拒絕'];

    //通用錯誤碼
    const  TOKEN_EXPIRED_ERROR    = ['code' => 1001, 'message' => 'token已過期','description'=>'token已過期，請重新登入'];
    const  TOKEN_INVALIDATE_ERROR = ['code' => 1002, 'message' => '無效token','description'=>'token格式錯誤或已被禁止'];
    const  TOKEN_REFRESH_ERROR    = ['code' => 1003, 'message' => '刷新token失敗','description'=>'刷新token結果失敗或被拒絕'];


    //登錄註冊錯誤
    const  LOGIN_ERROR            = ['code' => 1010, 'message' => '登錄失敗','description'=>'登錄結果失敗或被拒絕'];
    const  REGISTER_ERROR         = ['code' => 1011, 'message' => '註冊失敗','description'=>'註冊結果失敗或被拒絕'];

    const  REPEAT_PHONE_ERROR     = ['code' => 1014, 'message' => '手機重複註冊','description'=>'用戶手機已註冊'];
    const  USER_VALIDATE_ERROR    = ['code' => 1015, 'message' => '使用者驗證失敗','description'=>'無法驗證使用者身份'];
    //OTP驗證
    const  OTP_VALIDATE_ERROR     = ['code' => 1016, 'message' => 'OTP驗證失敗','description'=>'無法驗證使用者OTP'];
    const  OTP_NOT_EXIST_ERROR    = ['code' => 1017, 'message' => 'OTP驗證不存在','description'=>'驗證紀錄遺失或不存在'];
    const  OTP_CODE_ERROR         = ['code' => 1018, 'message' => 'OTP驗證碼錯誤','description'=>'使用者驗證碼錯誤'];
    const  OTP_INVALID_ERROR      = ['code' => 1019, 'message' => 'OTP驗證碼失效','description'=>'驗證碼已失效，請重新獲取'];

    //檔案驗證
    const  FILE_UPLOAD_ERROR      = ['code' => 1020, 'message' => '檔案上傳錯誤','description'=>'檔案上傳結果失敗或被拒絕'];
    const  FILE_UPLOAD_READ_ERROR = ['code' => 1021, 'message' => '檔案上傳無法讀取','description'=>'檔案上傳無法讀取或未上傳任何檔案'];
    const  FILE_UPLOAD_SIZE_ERROR = ['code' => 1022, 'message' => '檔案上傳內容過大','description'=>'檔案上傳內容過大，拒絕存取'];
    const  FILE_UPLOAD_TYPE_ERROR = ['code' => 1023, 'message' => '檔案上傳格式不符','description'=>'檔案上傳格式不符，拒絕存取'];
    const  FILE_DOWNLOAD_ERROR    = ['code' => 1024, 'message' => '檔案下載錯誤','description'=>'無法下載檔案內容'];

    //使用者權限
    const  PERMISSION_INSUFFICIENT= ['code' => 1025, 'message' => '使用者權限不足','description'=>'使用者無權訪問此功能'];
    const  VILLAGE_INVALID_ERROR  = ['code' => 1026, 'message' => '使用者所屬村里已變更','description'=>'使用者所屬村里已變更，無法執行此操作'];
    //1026-1035
    const  USER_RUN_ONCE          = ['code' => 1035, 'message' => '使用者不可重新訪問' , 'description'=>'該請求不允許該使用者重新訪問'];

    //1036-1039

    //健康手環
    const ACCOUNT_HAS_USER = ['code' => 1040, 'message' => '該帳號已擁有使用者' , 'description'=>'該帳號已擁有使用者，請輸入其他帳號'];

    //數據操作錯誤碼
    const  QUERY_ERROR           = ['code' => 2000, 'message' => "查詢失敗",'description'=>'查詢結果失敗或被拒絕'];
    const  CREATE_ERROR          = ['code' => 2001, 'message' => "創建失敗",'description'=>'創建結果失敗或被拒絕'];
    const  UPDATE_ERROR          = ['code' => 2002, 'message' => "更新失敗",'description'=>'更新結果失敗或被拒絕'];
    const  DELETE_ERROR          = ['code' => 2003, 'message' => "刪除失敗",'description'=>'刪除結果失敗或被拒絕'];
    const  NOT_EXIST_ERROR       = ['code' => 2004, 'message' => "記錄不存在",'description'=>'記錄遺失或不存在'];
    const  PARAM_INVALID_ERROR   = ['code' => 2005, 'message' => '參數校驗失敗','description'=>'缺少必要參數或資料格式不符'];
    const  UPDATE_NOT_ALLOWED    = ['code' => 2006, 'message' => '此資料不允许修改','description'=>'該筆資料不允許進行修改'];
    const  DELETE_NOT_ALLOWED    = ['code' => 2007, 'message' => '此資料不允许刪除','description'=>'該筆資料不允許進行刪除'];

    //2007-2015

    //Firebase
    const DEVICE_TOKEN_NOT_EXIST_ERROR = ['code' => 2015, 'message' => '使用者裝置Token不存在','description'=>'該使用者無裝置Token'];
    const DEVICE_VALIDATE_ERROR = ['code' => 2016, 'message' => '使用者裝置UUID不存在','description'=>'裝置UUID不符合'];
    //OTP驗證錯誤碼
    const OTP_SEND_ERROR= ['code'=>2010,'message'=>"不當獲取驗證碼操作",'description'=>'10分鐘內連續發送OTP驗證碼'];
    const CAPTCHA_CREATE_ERROR = ['code'=>2011,'message'=>"簡訊發送錯誤",'description'=>'請確認簡訊帳密正確與金額足夠'];

}
