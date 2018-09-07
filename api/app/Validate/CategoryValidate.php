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

class CategoryValidate extends BaseValidate
{
    protected $message = [
        'id.interger'     => 'ID必须为整数',
        'id.required'     => 'ID不能为空',
        'sort.required'   => '排序值必填',
        'sort.integer'    => '排序值必须为整数',
        'name.required'   => '名称不能为空',
        'name.max'        => '名称最多20个字符',
        'status.required' => '状态值不能为空',
        'status.in'       => '状态值不正确'
    ];

    public function __construct($request, $action_type = 'insert')
    {
        parent::__construct($request);
        $this->rule = [
            'id'     => 'sometimes|integer',
            'sort'   => 'required|integer',
            'name'   => 'required|max:20',
            'status' => ['required', Rule::in(['-1', '1'])],
        ];
    }

    /**
     * 自定义验证
     */
    protected function customValidate()
    {
        $where = [
            ['name', '=', $this->requestData['name']],
            ];
        if(isset($this->requestData['id'])){
            $where[] = ['id', '<>' , $this->requestData['id']];
        }
        $count = Category::where($where)->count();
        if($count > 0){
            $this->validator->errors()->add('name', '名称已存在');
        }
    }
}