<?php
/**
 * Created by PhpStorm.
 * User: chenf
 * Date: 19-5-13
 * Time: 上午10:20
 */

namespace app\common\exception;


use app\common\server\CrossDomain;
use app\common\server\Log;
use app\common\Traits\ApiResponse;

class BaseException
{
    use ApiResponse;

    public function __construct()
    {
        # 这个必须强制设置为true
        $this->is_anomaly_andling_takeover = true;

        # 检查当前异常处理是否继承了异常处理接管, 没有则抛出一个异常
        if(!($this instanceof CustomExceptionInterface)) {
            return $this->showMsg(__CLASS__ . '必须继承CustomExceptionInterface这个接口', []);
        }
    }

    public function getLevel() : int
    {
        return $this->level ?? 0;
    }

    public function showMsg($msg, array $error_info, $code = 500)
    {
        # 写入日志
        Log::setException()->setTitle($msg)->setLevel($this->getLevel())->setContent($error_info);

        CrossDomain::credentials();

        if(config('app.app_debug')) {
            return $this->status($msg, compact('error_info'), $code, $code);
        }else{
            return $this->status("服务器繁忙", [], $code, $code);
        }
    }
}