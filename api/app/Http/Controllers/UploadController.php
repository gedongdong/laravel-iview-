<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


use App\Lib\Exception\UploadException;

class UploadController extends BaseController
{
    protected $allow_mime = [
        'image/png', 'image/jpg', 'image/jpeg'
    ];
    protected $allow_extension = [
        'png', 'jpg', 'jpeg'
    ];

    public function upload()
    {
        $this->needToken();

        $photo = $this->request->file('photo');
        if (!$photo->isValid()) {
            throw new UploadException();
        }
        $path = $photo->store('upload/article', 'custom');

        $extension = $photo->extension();
        $mime_type = $photo->getMimeType();
        $size      = $photo->getSize();

        $data = [
            'extension' => $extension,
            'mime_type' => $mime_type,
            'size'      => $size,
        ];

        if (!in_array($extension, $this->allow_extension) || !in_array($mime_type, $this->allow_mime)) {
            $params = ['errcode' => 30001];
            return $this->jsonReturn($data, $params);
        }

        if ($size > config('custom.upload_max_size')) {
            $params = ['errcode' => 30002];
            return $this->jsonReturn($data, $params);
        }

        $data['url'] = config('app.url') . '/' . $path;
        return $this->jsonReturn($data);
    }


}