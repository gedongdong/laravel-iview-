<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Cache;

class UserController extends BaseController
{
    public function getUser()
    {
        $this->needToken();

        $user = decrypt(Cache::get($this->token));
        $data = [
            'user_id' => $user->id,
            'email'   => $user->email,
            'access'  => '',
            'avator'  => $user->avator
        ];
        return $this->jsonReturn($data);
    }
}