<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/7/13
 * Time: 11:06
 */

namespace App\Validate;


use App\Model\Category;
use Illuminate\Validation\Rule;

class ArticleValidate extends BaseValidate
{
    protected $message = [
        'id.interger'      => 'ID必须为整数',
        'id.required'      => 'ID不能为空',
        'cate_id.required' => '分类不能为空',
        'cate_id.integer'  => '分类必须是整数',
        'title.required'   => '标题不能为空',
        'title.max'        => '标题最多50个字符',
        'photo.url'        => '封面图url格式不正确',
        'content.required' => '内容不能为空',
        'summary.required' => '摘要信息不能为空',
        'summary.max'      => '摘要信息最多50个字符',
        'status.in'        => '状态值不正确',
        'is_top.in'        => '置顶状态值不正确',
        'sort.required'    => '排序值不能为空',
        'sort.integer'     => '排序值必须为整数'
    ];

    public function __construct($request, $action_type = 'insert')
    {
        parent::__construct($request);
        $this->rule = [
            'id'      => 'sometimes|required|integer',
            'cate_id' => 'required|integer',
            'title'   => 'required|max:50',
            'photo'   => 'sometimes|url',
            'content' => 'required',
            'summary' => 'required|max:50',
            'status'  => [Rule::in(['-1', '1'])],
            'is_top'  => [Rule::in(['0', '1'])],
            'sort'    => 'required|integer',
        ];
    }

    /**
     * 自定义验证
     */
    protected function customValidate()
    {
        $result = Category::find($this->requestData['cate_id']);
        if (!$result) {
            $this->validator->errors()->add('cate_id', '分类有误');
        }
    }
}