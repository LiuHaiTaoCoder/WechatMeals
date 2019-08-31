<?php
/**
 * Created by PhpStorm.
 * User: useristrator
 * Date: 2019/8/8
 * Time: 10:46
 */

namespace app\admin\model;

use think\Model;
use think\Session;
use think\Db;

class User extends Model
{
    protected $pk='user_id',$user;
    //获取单条信息
    public function getUserFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getUserList()
    {
        $res = $this->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getUserPage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addUserInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delUserInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upUserInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
    //判断修改的名称是否存在
    public function isNameExists($name,$id)
    {
        $res = $this->where('user_name',$name)->where($this->pk,'<>',$id)->find();
        return $res;
    }
    //前台登录
    public function indexLogin($data){
        $res = $this->where(['user_mobile'=>$data['user_mobile'],'user_pwd'=>md5(sha1($data['user_pwd'])),'user_status'=>1])->find();
       // dump(Db::getlastSql());
        if(!empty($res) && $res['user_status']==1){
           
        }
        return $res;
    }

}