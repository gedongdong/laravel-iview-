<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/7/13
 * Time: 11:42
 */

namespace App\Validate;


use App\Lib\Exception\ParameterException;
use Illuminate\Support\Facades\Validator;

class BaseValidate
{
    protected $rule;
    protected $message;

    // 请求数据
    public $requestData;

    protected $validator;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function goCheck()
    {
        $this->requestData = $this->getRequestData();

        $this->validator = Validator::make($this->request->all(), $this->rule, $this->message);
        $this->validator->after(function () {
            $this->customValidate();
        });

        if ($this->validator->fails()) {
            throw new ParameterException([
                'errmsg' => $this->validator->errors()
            ]);
        }
        return $this;
    }

    protected function getRequestData()
    {
        return $this->request->only(array_keys($this->rule));
    }

    /**
     * 自定义验证
     * 如需要，子类重写该方法
     */
    protected function customValidate()
    {
        // 子类重写
    }


    // 自定义验证规则
    protected function isPostiveInt($value)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function isMobile($value)
    {
        $rule   = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if($result){
            return true;
        }
        return false;
    }


}