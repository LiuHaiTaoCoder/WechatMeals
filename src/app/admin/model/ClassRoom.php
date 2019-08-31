<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 11:02
 */

namespace app\admin\model;

use think\Model;

class ClassRoom extends Model
{
    protected $name = 'class';
    protected $pk = 'class_id';
    public function initialize(){
        parent::initialize();
    }
    //获取单条信息
    public function getClassFind($id)
    {
       return $this->get($id);
    }
    //获取所有列表
    public function getClassList()
    {
        $res = $this->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getClassPage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addClassInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delClassInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upClassInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
    //判断修改的名称是否存在
    public function isNameExists($name,$id)
    {
        $res = $this->where('class_name',$name)->where('school_id','eq',$id)->find();
        return $res;
    }
}