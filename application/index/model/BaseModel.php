<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/10
 * Time: 17:07
 */

namespace app\index\model;


use think\Db;
use think\Model;

class baseModel extends Model
{
// 获取列表
    public function getList($where=[],$join=[],$field='*',$order='',$limit='') {
        $res_list = $this->alias('a')->where($where)->join($join)->field($field)->order($order)->limit($limit)->select();
        return $res_list;
    }
    // 获取单条数据
    public function getInfo($where = [], $join = [], $field = '*')
    {
        $res = $this->alias('a')->where($where)->join($join)->field($field)->find();
        return $res;
    }
    // 添加数据获取其id值
    public function addGetId($data)
    {
        $res = $this->insertGetId($data);
        return $res;
    }
    // 添加一条数据
    public function insertData($data)
    {
        $res = $this->insert($data);
        return $res;
    }
    // 添加多条数据
    public function insertAllData($arr)
    {
        $res = $this->insertAll($arr);
        return $res;
    }
    // 更新一条数据
    public function updateDate($data)
    {
        $res = $this->update($data);
        return $res;
    }
    // 删除数据
    public function deleteData($where)
    {
        $res = $this->where($where)->delete();
        return $res;
    }
    // 批量删除
    public function deleteAllData($where)
    {
        $res = $this->where($where)->delete();
        return $res;
    }
}