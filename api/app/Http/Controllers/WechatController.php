<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


class WechatController extends BaseController
{
    public function index()
    {
        logger('wechat debug', $_GET);
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        $echostr   = $_GET["echostr"];

        $token  = config('custom.wx_notice_token');

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            logger('wechat debug', ['success']);
            return $echostr;
        } else {
            logger('wechat debug', ['failed']);
            return 'false';
        }
    }

}