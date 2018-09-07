<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/13
 * Time: 17:15
 */

namespace App\Model;


use App\Lib\Enum\ArticleEnum;

class Article extends Base
{
    protected $table = 'article';

    public function getViewList($cate_id = 0, $order = [], $curr_page = 0, $page_size = 0)
    {
        $where = [];
        if ($cate_id > 0) {
            $where[] = ['cate_id', '=', $cate_id];
        }
        $fields = ['id', 'cate_id', 'title', 'status', 'photo', 'summary', 'pv', 'is_top', 'sort', 'created_at'];
        $this->listOrFail($where, $fields, $order, $curr_page, $page_size);
        $this->_joinList();
    }

    protected function _joinList()
    {
        $cate_arr = Category::getKeyValueList();
        foreach ($this->dataSourceList as $v) {
            $v->status_desc   = $v->status == ArticleEnum::Enable ? '启用' : '禁用';
            $v->is_top_desc   = $v->is_top == 1 ? '是' : '否';
            $v->cate_name     = $cate_arr[$v->cate_id];
            $this->viewList[] = $v;
        }

    }
}