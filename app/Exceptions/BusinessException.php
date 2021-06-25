<?php
/**
 * Created by PhpStorm.
 * User: wtone
 * Date: 2019/3/28
 * Time: 下午8:38
 */

namespace App\Exceptions;


use App\Constants\ErrorCode;
use Exception;
use Throwable;

/**
 * 业务核心异常处理类
 * Class BusinessException
 *
 * @package App\Exceptions
 */
class BusinessException extends Exception
{
    //数据
    private $data = [];
    private $description=null;


    public function __construct(array $code = ErrorCode::ERROR,$description="", $status = null, Throwable $previous = null)
    {
        $this->status = $status;
        $this->description=$description;
        if (empty($this->description)){
            if(empty($code['description'])){
                $this->description='';
            }else{
                $this->description=$code['description'];
            }
        }
        $errcode = $code['code'];
        $errmsg  = $code['message'];
        parent::__construct($errmsg, $errcode, $previous);
    }

    /**
     * 获取数据
     */
    public function getDescription(){
        return $this->description;
    }
    public function getStatusCode()
    {
        return $this->status;
    }
}