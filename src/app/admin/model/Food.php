<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 10:51
 */

namespace app\admin\model;

use think\Model;

class Food extends Model
{
    protected $pk = 'food_id',$food;
    //获取单条信息
    public function getFoodFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getFoodList()
    {
        $res = $this->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getFoodPage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addFoodInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delFoodInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upFoodInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
    //判断修改的名称是否存在
    public function isNameExists($name,$ids,$id)
    {
        $res = $this->where('food_name',$name)->where('menu_id','eq',$ids)->where($this->pk,'neq',$id)->find();
        return $res;
    }
}