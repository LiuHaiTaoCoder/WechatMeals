<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 14:59
 */

namespace app\admin\model;

use think\Model;
class Children extends Model
{
    protected $pk='children_id',$children;
//获取单条信息
    public function getChildrenFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getChildrenList()
    {
        $res = $this->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getChildrenPage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addChildrenInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delChildrenInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upChildrenInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
    //判断修改的名称是否存在
    public function isNameExists($name,$id)
    {
        $res = $this->where('Children_name',$name)->where($this->pk,'<>',$id)->find();
        return $res;
    }
}