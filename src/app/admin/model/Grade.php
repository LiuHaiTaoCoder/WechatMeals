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

class Grade extends Model
{
    protected $pk = 'grade_id',$grade;
    //获取单条信息
    public function getGradeFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getGradeList($limitRows = 10)
    {
        $res = $this->limit($limitRows)
            ->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getGradePage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addGradeInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delGradeInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upGradeInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
    //判断修改的名称是否存在
    public function isNameExists($name,$id)
    {
        $res = $this->where('grade_name',$name)->where($this->pk,'<>',$id)->find();
        return $res;
    }
}