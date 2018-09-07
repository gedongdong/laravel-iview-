<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/13
 * Time: 17:15
 */

namespace App\Model;


class Category extends Base
{
    protected $table = 'category';
    public $timestamps = false;

    public static function getKeyValueList()
    {
        $cate_key_value_arr = [];
        $categories = self::allOrFail(['id', 'name']);
        if ($categories) {
            foreach ($categories as $v) {
                $cate_key_value_arr[$v['id']] = $v['name'];
            }
        }
        return $cate_key_value_arr;
    }
}