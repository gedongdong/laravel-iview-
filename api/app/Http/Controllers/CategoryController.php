<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


use App\Lib\Enum\CategoryEnum;
use App\Lib\Exception\CategoryException;
use App\Lib\Exception\SuccessMessage;
use App\Model\Category;
use App\Validate\CategoryValidate;

class CategoryController extends BaseController
{
    public function index()
    {
        $category = Category::allOrFail();
        foreach ($category as &$cate) {
            $cate->status_desc = $cate->status == CategoryEnum::Enable ? '启用' : '禁用';
            $cate->label       = $cate->name . ' (' . $cate->status_desc . ')';
        }
        return $this->jsonReturn($category);
    }

    public function getCategoryByID()
    {
        $id   = $this->request->input('id', 0);
        $cate = Category::findOrFail((int)$id);
        return $this->jsonReturn($cate);
    }

    public function store()
    {
        $this->needToken();

        $validate = new CategoryValidate($this->request);
        $validate->goCheck();

        if (isset($validate->requestData['id']) && $validate->requestData['id'] > 0) {
            $cate = Category::findOrFail($validate->requestData['id']);
        } else {
            $cate = new Category();
        }
        foreach ($validate->requestData as $k => $v) {
            $cate->$k = $v;
        }

        if ($cate->save()) {
            throw new SuccessMessage();
        }
        throw new CategoryException(['errcode' => 50001]);
    }

}