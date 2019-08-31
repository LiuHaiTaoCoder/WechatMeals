<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/22
 * Time: 20:44
 */
namespace app\index\controller;

use think\Controller;
use think\Db;

class Person extends Controller
{
    public function index()
    {
        vendor('wxpay.wechat');
        $wchat = new \WechatOauth();
        $code = request()->param('code',"");
        $data = $wchat->getUserAccessUserInfo($code);
        
        if($data){
        	$find = Db::name('user')->where(["open_id"=>$data['openid']])->find();
        	if($find){
        		if($find["user_mobile"] != 0){
        			$ip = request()->ip();
	                $user_id = $find['user_id'];
	                Db::name('user')->where('user_id',$user_id)->update(['login_number' => $find['login_number']+1]);
	                Db::name('user_log')->insert(
	                    [
	                        'ip_address' => $ip,
	                        'login_info' => "微信授权登录成功",
	                        'user_id' => $user_id,
	                    ]
	                );
        			//保存登录信息
        			session('user_id',$find['user_id']);
        			cookie('user',$find);
        			
            		$this->redirect('/index/index/index');
        		}else{
        			
        			session("open_id",$find["open_id"]);
        			$this->redirect('/index/login/register');
        			//跳注册。保存id
        		}
        	}else{
        		$add['user_img']  = $data["headimgurl"]; 
	        	$add['open_id']  = $data["openid"];
	        	$add['user_nickname']  = $data["nickname"];
	        	$add['user_sex']  = $data["sex"];
	        	$res = DB::name("user")->insert($add);
	        	session("open_id",$find["open_id"]);
        		$this->redirect('/index/login/register');
        	}
        	
        }
        
    }
}