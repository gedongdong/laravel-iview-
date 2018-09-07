<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/17
 * Time: 16:40
 */

namespace App\Http\Extend;


class ErrorMessage
{
    const MSG = [
        '0' => 'ok',

        '10001' => 'token验证失败或已过期，请重新登录',
        '10002' => 'token获取失败',

        '20000' => '表单校验失败',

        '30000' => '文件上传失败',
        '30001' => '文件类型错误',
        '30002' => '文件大小超过限制',

        '40004' => '查询没有数据',

        '50001' => '服务器保存失败',
        '99999' => '服务器内部错误'
    ];
}