<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 17:09
 */
namespace app\admin\model;

use think\Model;
use think\Request;
use think\Db;
use think\Cookie;
use think\Session;

class Index extends Model
{
    protected $table = 'admin';
    protected $pk = 'admin_id';
    //初始化
    public function _initialize()
    {
        parent::initialize();
        $this->table = new \stdClass;
    }
    //验证登录信息
    public function adminLogin($data)
    {
        $res = Db::name($this->table)
            ->where(['admin_name'=>$data['admin_name'],'admin_pwd'=>md5(sha1($data['admin_pwd'])),'admin_status'=>1])
            ->find();
        if(!empty($res)){
            $ip = request()->ip();
            $admin_id = $res['admin_id'];
            Db::name($this->table)->where('admin_id',$admin_id)->update(
                [
                    'login_number' => $res['login_number']+1
                ]
            );
            Db::name('admin_log')->insert(
                [
                    'ip_address' => $ip,
                    'login_info' => "login success",
                    'admin_id' => $admin_id,
                ]
            );
            //设置Cookie时间 2小时
            Cookie::set('admin_login',$res,60*60*2);
          /*  //设置Session值
            Session::set('admin_id',['admin_id' => $res['admin_id']]);*/
        }
        return $res;
    }
    //获取单条信息
    public function getAdminFind($arr = [])
    {
        $arr['where'] ='';
        $data = Db::name($this->table)->where($arr['where'])->find();
        return $data;
    }

    //获取管理员列表
    public function getAdminList($arr = [])
    {
        $res = Db::name($this->table)->where($arr['where'])->order($arr['order'])->select();
        return $res;
    }
    //获取分页内容
    public function getPageList($arr = [],$page)
    {
        $res = Db::name($this->table)->where($arr['where'])->order($arr['order'])->paginate($page);
        return $res;
    }

    //添加管理员信息
    public function addAdminInfo($data = [])
    {
        $res = Db::name($this->table)->insert($data);
        return $res;
    }
    //修改管理员信息
    public function upAdmininfo($id,$data = [])
    {
        $res = Db::name($this->table)->where('admin_id',$id)->update($data);
        return $res;
    }
    //删除管理员信息
    public function delAdmininfo($id)
    {
        $res = Db::name($this->table)->where('admin_id',$id)->delete();
        return $res;
    }
    //判断修改的名称是否存在
    public function isNameExists($name,$id)
    {
        $res = Db::name('admin')->where('admin_name',$name)->where($this->pk,'<>',$id)->find();
        return $res;
    }
}