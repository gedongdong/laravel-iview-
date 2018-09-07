<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


use App\Lib\Exception\TokenGetException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends BaseController
{
    use AuthenticatesUsers;

    public function getToken()
    {
        if ($user = $this->login($this->request)) {
            $token = md5($user->id);

            $token_value = encrypt($user);
            Cache::put($token, $token_value, 120);

            $data = ['token' => $token];
            return $this->jsonReturn($data);
        }
        throw new TokenGetException();
    }

    protected function loggedOut()
    {
        return $this->jsonReturn();
    }

    protected function authenticated(Request $request, $user)
    {
        return $user;
    }
}