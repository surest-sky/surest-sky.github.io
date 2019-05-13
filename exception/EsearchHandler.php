<?php
/**
 * Created by PhpStorm.
 * User: chenf
 * Date: 19-5-13
 * Time: 上午9:32
 */

namespace app\common\exception;


// 用户接受处理ElasticSearch处理的一些错误

class EsearchHandler extends BaseException implements CustomExceptionInterface
{
    public function handler($e, array $error_info)
    {
        $e_class = get_class($e);

        switch ($e_class) {
            case 'Elasticsearch\Common\Exceptions\UnexpectedValueException':
                return $this->showMsg($e->getMessage(), $error_info);
                break;
            case 'Elasticsearch\Common\Exceptions\BadRequest400Exception' :
                return $this->showMsg(json_decode($e->getMessage(), true), $error_info);
                break;
        }

        # 否则不返回错误异常
    }
}