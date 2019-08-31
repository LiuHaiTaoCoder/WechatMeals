<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 15:43
 */
namespace app\index\controller;

use think\Cookie;
use think\Request;
use think\Session;
use think\Config;
use think\Db;
use think\App;
use think\Exception;

class User extends Base
{
    protected $rst,$user,$children,$sms;
    public function _initialize()
    {
        //初始化继承的控制器
        parent::_initialize();
        vendor('Sms.ChuanglanSmsHelper.ChuanglanSmsApi');
        $this->sms = new \ChuanglanSmsApi();
        $this->rst = Request::instance();
        $this->user = new \app\admin\model\User();
        $this->children = new \app\admin\model\Children();
    }
    //用户个人中心
    public function personal()
    {
        $id = Session::get('user_id');
        $result = $this->user->getUserFind($id);
        $info = Db::name('children')->where('user_id',$id)->select();

        $this->assign('res',$result);
        $this->assign('info',$info);
        return $this->fetch('core');
    }
    
    //查看学生详细信息
    public function students()
    {
        $id = $this->rst->param('id');
        $ids = $this->rst->param('ids');
        $res = Db::name('children')
            ->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->join('grade c','a.grade_id=c.grade_id')
            ->join('class d','a.class_id=d.class_id')
            ->field('a.*,b.school_name,c.grade_name,d.class_name')
            ->where('children_id',$id)
            ->find();
        $sc = Db::name('school')->where('school_id','<>',$ids)->select();

        $this->assign('sc',$sc);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //修改学生详细信息
    public function studentsUpdate()
    {
        $data = $this->rst->post();
        $id = $this->rst->post('children_id');
        $data['user_id'] = Session::get('user_id');
        //判断该学校，用户，班级是否存在学生
        $isName = Db::name('children')->where(['children_name'=> $data['children_name']
            ,'class_id'=>$data['class_id']])->where('user_id','<>',$data['user_id'])->find();
        if(!$isName){
            $data['update_time'] = time();
            $res = $this->children->upChildrenInfo($id,$data);
            if($res){
                $xhr = ['status'=>1,'msg'=>'修改成功'];
            }else{
                $xhr = ['status'=>0,'msg'=>'修改失败'];
            }
        }else{
            $xhr = ['status'=>2,'msg'=>'修改失败，已存在该学生!'];
        }
        return json_encode($xhr);
    }
    //解绑学生信息
    public function studentsDel(){
        $id = $this->rst->param('id');
        $res = $this->children->delChildrenInfo($id);
        return $res;
    }
    //用户修改密码
    public function modifyPar()
    {
        return $this->fetch('modify');
    }
    public function modifyParUpdate()
    {
        $id = Session::get('user_id');
        $data= $this->rst->post();
        $oldpwd =  $this->rst->post('old_pwd');
        $isPass = Db::name('user')->where(['user_id'=>$id,'user_pwd'=>md5(sha1($oldpwd))])->find();
        if($isPass){
            $newData['user_pwd'] = md5(sha1($data['user_pwd']));
            $result = $this->user->upUserInfo($id,$newData);
            if($result){
                $xhr = ['status'=>1,'msg'=>'修改成功,下次登录时生效'];
            }else{
                $xhr = ['status'=>0,'msg'=>'新密码不能与旧密码一致'];
            }
        }else{
            $xhr = ['status'=>2,'msg'=>'旧密码不正确!'];
        }
        return json_encode($xhr);
    }


}