<?php

namespace App\Exceptions;

use App\Http\Extend\ErrorMessage;
use App\lib\exception\BaseException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    private $code;
    private $errmsg;
    private $errcode;

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof BaseException) {
            //自定义异常
            $this->code    = 200;
            $this->errcode = $exception->errcode;
            $this->errmsg  = $exception->errmsg ?: ErrorMessage::MSG[$this->errcode];
        } else {
            if (config('app.debug')) {
                return parent::render($request, $exception);
            } else {
                $this->code    = 500;
                $this->errmsg  = ErrorMessage::MSG[99999];
                $this->errcode = 99999;
            }
        }
        $result = [
            'errmsg'      => $this->errmsg,
            'errcode'     => $this->errcode,
            'request_url' => $request->url(),
        ];
        return response()->json($result, $this->code);
    }
}
