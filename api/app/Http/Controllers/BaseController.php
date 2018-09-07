<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


use App\Http\Extend\ErrorMessage;
use App\Lib\Exception\TokenException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BaseController extends Controller
{
    protected $requestUrl;
    protected $request;
    protected $token;

    public function __construct(Request $request)
    {
        $this->requestUrl = $request->url();
        $this->request    = $request;
    }

    protected function needToken()
    {
        $token = $this->request->input('token', '');
        if (!Cache::has($token)) {
            throw new TokenException();
        }
        $this->token = $token;
    }

    protected function jsonReturn($data = [], $params = [])
    {
        $result = [
            'errcode' => 0,
            'errmsg'  => 'ok',
            'url'     => $this->requestUrl,
            'data'    => $data
        ];

        if (array_key_exists('errcode', $params)) {
            $result['errcode'] = $params['errcode'];
        }

        $result['errmsg'] = ErrorMessage::MSG[$result['errcode']];

        return response()->json($result);
    }
}