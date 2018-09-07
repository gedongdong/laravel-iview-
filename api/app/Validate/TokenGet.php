<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/7/13
 * Time: 11:06
 */

namespace App\Validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require'
    ];

    protected $message = [
        'code' => '请检查参数'
    ];
}