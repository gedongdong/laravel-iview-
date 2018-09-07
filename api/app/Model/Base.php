<?php
/**
 * Created by PhpStorm.
 * User: gedongdong
 * Date: 2018/8/13
 * Time: 17:29
 */

namespace App\Model;


use App\Lib\Exception\NoDataException;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    protected $dataSourceList; //数据库查询原始数据
    public $total = 0;
    public $viewList = []; //通过聚合、拼接等处理之后的视图数据

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function allOrFail($columns = ['*'])
    {
        $result = parent::all($columns);
        if (!$result->isEmpty()) {
            return $result;
        }
        throw new NoDataException();
    }

    public function listOrFail($where = [], $fields = [], $order = [], $curr_page = 0, $page_size = 0)
    {
        $query = new static();
        if ($fields) {
            $query = $query->select($fields);
        }
        if ($where) {
            $query = $query->where($where);
        }
        $total  = $query->count();

        if ($order) {
            $query = $query->orderBy($order[0], $order[1]);
        } else {
            $query = $query->orderBy('id', 'DESC');
        }
        if ($order) {
            $query = $query->orderBy($order[0], $order[1]);
        }
        if ($curr_page && $page_size) {
            $query = $query->offset(($curr_page - 1) * $page_size)
                ->limit($page_size);
        }
        $result = $query->get();
        if ($result->isEmpty()) {
            throw new NoDataException();
        }
        $this->dataSourceList = $result;
        $this->total    = $total;
    }

    public static function findOrFail($id)
    {
        $result = self::find($id);
        if (!$result) {
            throw new NoDataException();
        }
        return $result;
    }


    // protected function get(){
    //     $this->connection;
    //     return parent::get();
    // }
}