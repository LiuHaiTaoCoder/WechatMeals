<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 14:56
 */

namespace app\admin\model;


use think\Model;

class Leave extends Model
{
    protected $pk='leave_id',$leave;
//获取单条信息
    public function getLeaveFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getLeaveList()
    {
        $res = $this->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getLeavePage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addLeaveInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delLeaveInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upLeaveInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
}