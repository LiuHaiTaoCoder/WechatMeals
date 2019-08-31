<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 18:28
 */

namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Cookie;
use think\Session;
use think\captcha;

class Login extends Controller
{
    private $admin;
    public function _initialize()
    {
        $this->admin = new \app\admin\model\Index();
    }
    public function Index()
    {
        return $this->fetch();
    }
    //后台登录判断
    public function login()
    {
        $xhr = [];
        $request = Request::instance();
        $data = $request->post();

        if(!captcha_check($data['code']))
        {
            $xhr  = ['status'=>'0','msg'=>'验证码错误 请重试'];
           return json_encode($xhr);
        }

        $result = $this->admin->adminLogin(
            [
                'admin_name' => $data['admin_name'],
                'admin_pwd' => $data['admin_pwd'],
            ]
        );

        if($result){
            $xhr = ['status'=>'1','msg'=>'登录成功!'];
        }else{
            $xhr = ['status'=>'2','msg'=>"用户名或密码错误请重试!"];
        }
        return json_encode($xhr);
    }
    //退出登录
    public function quitLogin()
    {
        $xhr = [];
        if(Cookie::has('admin_login'))
        {
            Cookie::delete('admin_login');
            $xhr = ['status'=>'1','msg'=>'正在退出中!'];
        }
        return json_encode($xhr);
    }
}