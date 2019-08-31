<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 15:50
 */

namespace app\index\controller;

use think\Console;
use think\Controller;
use think\Request;
use think\Cookie;
use think\Session;
use think\Config;
use think\View;
use think\Db;
use think\Exception;

class Login extends Controller
{
    protected $rst,$user,$sms;
   public function _initialize()
    {
    	/*if(Cookie::has('user') && Session::has('user_id')){
    		$this->redirect('/index/index/index');
    	
    	}*/
        vendor('Sms.ChuanglanSmsHelper.ChuanglanSmsApi');
        $this->user = new \app\admin\model\User();
        $this->rst = Request::instance();
        $this->sms = new \ChuanglanSmsApi();
    }
    //注册页面
    public function register()
    {	
        return $this->fetch('register');
    }
    //验证码发送
    public function sendCode()
    {
        $code = mt_rand(100000,999999);
        $tel = $this->rst->param('user_mobile');
        /*发送短信操作  参数1.验证码  2.手机号码*/
       $result = $this->sms->sendSMS($tel,'【新之光餐饮】亲爱的用户，您的短信验证码为'.$code.'，5分钟内有效，若非本人操作请忽略。');
       $outCode = json_decode($result,true);
       if($outCode){
            $arr = ['tel'=>$tel,'code'=>$result];
            //将验证码写入Cookie,5分钟内有效
            Cookie::set('reg'
                ,$arr
                ,300);
            $xhr = ['status'=>'1','code'=>$result];
        }else{
            $xhr = ['status'=>'0','code'=>'发送失败'];
        }
        return json_encode($xhr);
    }
    //验证码发送
    public function sendLoginCode()
    {
        $code = mt_rand(100000,999999);
        $tel = $this->rst->param('user_mobile');
        /*发送短信操作  参数1.验证码  2.手机号码*/
        $result = $this->sms->sendSMS($tel,'【新之光餐饮】您的登录验证码是'.$code.'，验证码有效期为5分钟，请尽快登录');
        $outCode = json_decode($result,true);
        if($outCode){
            $arr = ['tel'=>$tel,'code'=>$code];
            //将验证码写入Cookie,5分钟内有效
            Cookie::set('login',$arr,300);
            $xhr = ['status'=>'1','code'=>$result];
        }else{
            $xhr = ['status'=>'0'];
        }
        return json_encode($xhr);
    }

    //注册提交页面
    public function registerInsert()
    {
        $data = $this->rst->post();
       
        $data['user_pwd'] = md5(sha1($data['user_pwd']));
        $openid = session('open_id');
        $isMobile = Db::name('user')->where('user_mobile',$data['user_mobile'])->find();
        if(!$isMobile){
            $reg = Cookie::get('reg');
            //验证码判断
            if($data['code'] != $reg['code'] && $data['user_mobile'] != $reg['tel']){
                $xhr = ['status'=>'2','msg'=>'验证码不正确'];
            }else{
            
                $newdata['mobile_status'] = 1;
                $newdata['create_time'] = time();
                $newdata['user_pwd'] = $data['user_pwd'];
                $newdata['user_mobile'] = $data['user_mobile'];
                $newdata['user_name'] = $data['user_name'];
                $result = Db::name('user')->where('open_id',$openid)->update($newdata);
                
                if($result){
                    $xhr = ['status'=>'1'];
                }else{
                    $xhr = ['status'=>'0'];
                }
            }
        }else{
            $xhr = ['status'=>'3','msg'=>'该手机号已经注册'];
        }
        return $xhr;
    }
    //注册成功页面
    public function regSuccess()
    {
        return $this->fetch('success');
    }
    //密码登录页面
    public function passLoginPage()
    {
        return $this->fetch("login/login");
    }
    //验证密码登录
    public function passLogin()
    {
        $data = $this->rst->post();
        $isMobile = Db::name('user')->where('user_mobile',$data['user_mobile'])->find();
        if($isMobile){
            $result =  $this->user->indexLogin($data);
            if($result && $result['user_status'] == 1){
            		$ip = request()->ip();
		            $user_id = $result['user_id'];
		            Db::name('user')->where('user_id',$user_id)->update(
		                [
		                    'login_number' => $result['login_number']+1
		                ]
		            );
		            Db::name('user_log')->insert(
		                [
		                    'ip_address' => $ip,
		                    'login_info' => "密码登录成功",
		                    'user_id' => $user_id,
		                ]
		            );
		            //设置Session时间
		            Session::set('user_id',$user_id);
		            //设置Cookie值2小时
		            cookie('user',$result);
            	
                $xhr = ['status'=>'1','msg'=>"登录成功"];
            }else{
            	 $xhr = ['status'=>'3','msg'=>"用户已被停用或者密码错误"];
            }
        }else{
            $xhr = ['status'=>'2','msg'=>'该用户不存在'];
        }
         return json_encode($xhr);
    }
    //验证码登录
    public function codeLoginPage()
    {
        return $this->fetch('Codelogin');
    }
    //验证验证码登录
    public function codeLogin()
    {
        $data = $this->rst->post();
        $arr = Cookie::get('login');
        if($data['user_mobile'] == $arr['tel'] && $data['code'] == $arr['code']){
            $res = Db::name('user')->where('user_mobile',$data['user_mobile'])->find();
            if($res && $res['user_status'] == 1){
                $ip = request()->ip();
                $user_id = $res['user_id'];
                Db::name('user')->where('user_id',$user_id)->update(['login_number' => $res['login_number']+1]);
                Db::name('user_log')->insert(
                    [
                        'ip_address' => $ip,
                        'login_info' => "验证码登录成功",
                        'user_id' => $user_id,
                    ]
                );
                //设置Session时间
                Session::set('user_id',$user_id);
                //设置Cookie值2小时
                cookie('user',$res);
                $xhr = ['status'=>'1','msg'=>'登录成功'];
            }else if($result['status'] == 0){
            	 $xhr = ['status'=>'3','msg'=>"用户已被停用"];
            }else{
                $xhr = ['status'=>'0','msg'=>'手机号未注册'];
            }
        }else{
            $xhr = ['status'=>'2','msg'=>'验证码不正确'];
        }
        return json_encode($xhr);
    }
    //退出登录
    public function quitLogin()
    {
        if(Session::has('user_id') && Cookie::has('user')){
            Session::delete('user_id');
            Cookie::delete('user');
            $xhr = ['status'=>1,'msg'=>'您已退出登录'];
        }else{
            $xhr = ['status'=>0,'msg'=>'退出失败'];
        }
        echo  json_encode($xhr);
    }
     //找回登录密码
    public function forgetPar()
    {
        return $this->fetch('forgetPar');
    }
    //找回登录密码验证码
    public function forgetCode()
    {
        $code = mt_rand(100000,999999);
        $tel = $this->rst->param('user_mobile');
        /*发送短信操作  参数1.验证码  2.手机号码*/
        $result = $this->sms->sendSMS($tel,'【新之光餐饮】您正在找回登录密码，验证码为'.$code.'，请于5分钟内使用，工作人员不会向您索取，请勿泄露');
        $outCode = json_decode($result,true);
        if($outCode){
            $arr = ['tel'=>$tel,'code'=>$code];
            //将验证码写入Cookie,5分钟内有效
            Cookie::set('forget',$arr,300);
            $xhr = ['status'=>'1','code'=>$result];
        }else{
            $xhr = ['status'=>'0'];
        }
        return json_encode($xhr);
    }
    //执行回登录密码
    public function forgetParUpdate()
    {
        $data = $this->rst->post();
        $isMobile = Db::name('user')->where('user_mobile',$data['user_mobile'])->find();
        $arr = Cookie::get('forget');
         if($data['user_mobile'] == $arr['tel'] && $data['code'] == $arr['code']){
             if($isMobile) {
                 $result = Db::name('user')->where('user_mobile',$data['user_mobile'])
                          ->setField('user_pwd',md5(sha1($data['user_pwd'])));
                 if($result){
                     $xhr = ['status'=>1,'msg'=>'重置密码成功'];
                 }else{
                     $xhr = ['status'=>0,'msg'=>'重置密码失败'];
                 }

             }else{
                 $xhr = ['status'=>2,'msg'=>'该手机号未注册'];
             }
         }else{
            $xhr = ['status'=>3,'msg'=>'验证码不正确'];
         }
        return json_encode($xhr);
    }
}