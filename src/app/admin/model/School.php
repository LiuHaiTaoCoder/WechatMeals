<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 10:46
 */

namespace app\admin\model;

use think\Model;
use think\Session;
use think\Cookie;
use think\Db;
use think\Request;

class School extends Model
{
    protected $pk = 'school_id',$school;
    //获取单条信息
    public function getSchoolFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getSchoolList()
    {
        $res = $this->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getSchoolPage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addSchoolInfo($data = [])
    {
        $res = $this->allowField(true)
                ->save($data);
        return $res;
    }
    //删除单条信息
    public function delSchoolInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upSchoolInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
    //判断修改的名称是否存在
    public function isNameExists($name,$id)
    {
        $res = $this->where('school_name',$name)->where($this->pk,'<>',$id)->find();
        return $res;
    }
}