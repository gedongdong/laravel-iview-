<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TokenController extends BaseController
{
    use AuthenticatesUsers;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->needToken();
    }

    public function tokenCheck()
    {
        $data = [
            'token_validate' => true
        ];
        return $this->jsonReturn($data);
    }

    public function deleteToken()
    {
        Cache::forget($this->token);
        return $this->loggedOut();
    }

    protected function loggedOut()
    {
        return $this->jsonReturn();
    }
}