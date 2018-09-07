<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/7/13
 * Time: 14:41
 */

namespace App\Lib\Exception;


use Exception;

class BaseException extends Exception
{
    public $errcode = 0;
    public $errmsg  = [];

    public function __construct($param = [])
    {
        if (!is_array($param)) {
            return;
        }

        if (array_key_exists('errcode', $param)) {
            $this->errcode = $param['errcode'];
        }
        if (array_key_exists('errmsg', $param)) {
            $this->errmsg = $param['errmsg'];
        }
    }
}