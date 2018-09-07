<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/9
 * Time: 16:34
 */

namespace App\Http\Controllers;


use App\Lib\Exception\ArticleException;
use App\Lib\Exception\SuccessMessage;
use App\Model\Article;
use App\Validate\ArticleValidate;

class ArticleController extends BaseController
{
    public function index()
    {
        $page      = $this->request->input('page', 1);
        $cate_id   = $this->request->input('cate', 0);
        $page_size = config('custom.page_size', 10);

        $article = new Article();
        $article->getViewList($cate_id, [], $page, $page_size);
        $data = [
            'total' => $article->total,
            'list'  => $article->viewList
        ];
        return $this->jsonReturn($data);
    }

    public function getArticleByID()
    {
        $id   = $this->request->input('id', 0);
        $cate = Article::findOrFail((int)$id);
        return $this->jsonReturn($cate);
    }

    public function store()
    {
        $this->needToken();

        $validate = new ArticleValidate($this->request);
        $validate->goCheck();

        if (isset($validate->requestData['id'])) {
            $article = Article::findOrFail($validate->requestData['id']);
        } else {
            $article = new Article();
        }
        foreach ($validate->requestData as $k => $v) {
            $article->$k = $v;
        }

        if ($article->save()) {
            throw new SuccessMessage();
        }
        throw new ArticleException(['errcode' => 50001]);
    }


}