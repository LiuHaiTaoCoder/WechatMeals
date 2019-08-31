<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 9:22
 */
namespace app\api\controller;

use think\Request;
use think\Db;
use think\Config;
use think\Controller;

class Wxpay extends Controller
{
	//初始化数据
    protected $rst,$appid,$appsecret,$mchid,$appkey,$uid;
    public function _initialize()
    {
    	vendor('wxpay.wxpayClass');
        $this->rst = Request::instance();
        $this->uid = session('user_id');
        /*if(!$this->uid){
            $this->redirect('/index/login/passLoginPage');
        }*/
        $this->appid = Config::get('app_id');
        $this->appsecret = Config::get('app_secret');
        $this->mchid = Config::get('mch_id');
        $this->appkey = Config::get('app_key');
    }
    //支付异步回调地址
    public function notify()
    {
        $pay = new \WxPay();
        $res = $pay->notify();
        //这段适用于测试数据，写入文件,正式上线后就能删除了
       // file_put_contents('filename.txt', print_r($res, true));
        if($res){
            //验证成功则更改数据库数据
            $data['wx_trade_no'] = $res['transaction_id'];
            $data['trade_no'] = $res['out_trade_no'];
            $data['pay_money'] = $res['total_fee']/100;
            $data['pay_time'] = time();
            $data['order_status'] = 1;

			Db::name('payback')->where('trade_no',$res['out_trade_no'])->setField('payback_status',1);
            Db::name('order')->where('trade_no',$res['out_trade_no'])->update($data);
        }
    }
    //调用支付
    public function pay()
    {
    	$isPayTime = Db::name('paytime')->order('paytime_id desc')->find();
    	$today = date('Y-m-d');
    	//判断是否在交费时间段
    	if($today>=$isPayTime['start_date'] && $today<=$isPayTime['end_date'] ){
	        $info = Db::name('user')->where('user_id',$this->uid)->field('open_id,user_id')->find();
	        $openid = $info['open_id'];
			$param = session('order');
			$trade_no = $this->rst->param('trade_no');
			if($trade_no){
				$getData = Db::name('order')->alias('a')->join('menu b','a.menu_id=b.menu_id')
				->field('a.*,b.menu_name')
				->where('trade_no',$trade_no)
				->find();
			
		        $pay = new \WxPay();
		        //支付结果 $param['pay_money']
		        $result = $pay->wxpay($openid,$this->rst->param('pay_money'),$getData['menu_name'],$getData['trade_no']);
		        $children_id = $getData['children_id'];
			}else{
		        $pay = new \WxPay();
		        //支付结果 $param['pay_money']
		        $result = $pay->wxpay($openid,$param['pay_money'],$param['menu_name'],$param['trade_no']);
		        $children_id = $param['children_id'];
			}
	    	$this->assign('id',$children_id);
	        $this->assign('data',$result);
	        return $this->fetch();
    	}else{
	    	echo "<script type='text/javascript'>alert('抱歉 当前未到交费时间');window.history.back(-1)</script>";
	    }	
	}
	//退款
	public function refund()
	{
	   	$pay = new \WxPay();
	   	$order = $this->rst->param('id');
	   	$trade_no = $this->rst->param('trade_no');
	   	if($trade_no){
	   		$status['leave_status'] = $this->rst->get('leave_status');
	    	$set = Db::name('leave')->where('trade_no',$trade_no)->update($status);
	    	 //查询订单表与退款表，取出相应的参数
			$info = Db::name('leave')->alias('a')
			    	->join('order b','a.trade_no=b.trade_no')
			    	->field('a.*,b.wx_trade_no,b.trade_no')
			    	->where('trade_no',$trade_no)
			    	->find();
	   	}else{
	    	$status['refund_status'] = $this->rst->get('refund_status');
	    	$set = Db::name('refund')->where('refund_id',$order)->update($status);
	    	 //查询订单表与退款表，取出相应的参数
			$info = Db::name('refund')->alias('a')
			    	->join('order b','a.trade_no=b.trade_no')
			    	->field('a.*,b.wx_trade_no,b.trade_no')
			    	->where('refund_id',$order)
			    	->find();
	    }
	    if($set){
		   	$result = $pay->refund($info['wx_trade_no'],$info['refund_no'],$info['pay_money'],$info['refund_money']);
		   	return $result;
	    }
    }
    
    
}