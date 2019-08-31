<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 16:13
 */

namespace app\index\controller;

use think\Cookie;
use think\Request;
use think\Session;
use think\Config;
use think\Db;
use think\App;
use think\Exception;

class Leave extends Base
{
    protected $rst,$leave,$children,$order;
    public function _initialize()
    {
        //初始化继承的控制器
        parent::_initialize();
        $this->rst = Request::instance();
        $this->leave = new \app\admin\model\Leave();
        $this->children = new \app\admin\model\Children();
        $this->order = new \app\admin\model\Order();
    }
    //我的请假列表
    public function myLeave()
    {
        $uid = Session::get('user_id');
        $id = $this->rst->param('id');
        if(!$id){
        	$this->redirect('/index/index/index');
        }
        $res = Db::name('leave')->where(['user_id'=>$uid,'children_id'=>$id])->select();
        foreach ($res as $k => $v){
            $res[$k]['start_date'] = substr(str_replace('-',"月",$v['start_date']),8,8);
            $res[$k]['end_date'] = substr(str_replace('-',"月",$v['end_date']),8,8);
        }
        $this->assign('res',$res);
        return $this->fetch('myLeave');
    }
    public function isTime(){
        /*请假的时间段,上午9点之前才能请假*/
        $leaveTime = date("Y-m-d",time());
        $resTime = strtotime($leaveTime.'9:00:00');
        $currTime = time();
        if($currTime >= $resTime){
            $xhr = ['status'=>0];
        }else{
            $xhr = ['status'=>1];
        }
        return $xhr;
    }
    //我要请假
    public function Leave()
    {
        $id = $this->rst->param('id');
        $info = Db::name('order')->where(['children_id'=>$id,'user_id'=>Session::get('user_id')])->where('order_status','<>',0)->order('create_time desc')->find();

        $this->assign('info',$info);
        return $this->fetch();
    }
    //插入请假发送
    public function LeaveInsert()
    {
        $uid = Session::get('user_id');
        $data = $this->rst->post();
        $newArr = json_decode(Cookie::get('user'));
        $data['user_name'] = $newArr->user_name;
        $data['user_id'] = $uid;
        //查询是否存在订单或者在不在请假时间段内
        $isOrder = Db::name('order')->where(['children_id'=>$data['children_id'],'user_id'=>$uid])->where('order_status','<>',0)->order('create_time desc')->find();
        //获取今天的日期
        $toDay = date('Y-m-d');
        //查询相对应购买套餐的价格
        $price= Db::name('order')
                ->alias('a')
                ->join('menu b','a.menu_id=b.menu_id')
                ->where(['user_id'=>$uid,'children_id'=>$data['children_id']])
                ->field('a.trade_no,a.pay_money,b.*')
                ->find();
        //查询退款的金额            
    	$isMoney = Db::name('refund')->where(['trade_no'=>$price['trade_no'],'refund_status' => 2])->sum('refund_money');        
    	$isMoneyTo = Db::name('leave')->where(['trade_no'=>$price['trade_no'],'leave_status' => 2])->sum('refund_money');  
	    if($isMoney+$isMoneyTo >= $price['pay_money']){
		 	$xhr = ['status'=>4,'msg'=>'该订单已经全部退款！'];
	    }else{
	    	 //判断是否存在订单以及是否在请假的时间段内
    		 if($isOrder && $toDay>$isOrder['start_date'] && $toDay<$isOrder['end_date']){
        		$isDay = Db::name('leave')->where(['start_date'=>$data['start_date'],'end_date'=>
                $data['end_date'],'user_id'=>$uid,'children_id'=>$data['children_id']])->find();
	            if(!$isDay){
	                $data['leave_day'] = floor((strtotime($data['end_date']) - strtotime($data['start_date']))/86400);//60s*60min*24h
	                $data['trade_no'] = $price['trade_no'];//获取订单号
	                $data['refund_no'] = getRefundNo();;//退款的订单号
	                $data['refund_money'] = $price['menu_price']*$data['leave_day'];//退款金额乘以相对应套餐单价和天数
	                $res = Db::name('leave')->insert($data);
	                if($res){
	                    $xhr = ['status'=>1,'msg'=>'请假成功,请等待审核'];
	                }else{
	                    $xhr = ['status'=>0,'msg'=>'请假失败'];
	                }
	            }else{
	                $xhr = ['status'=>3,'msg'=>'该时间段已经请过假'];
	            }
	        }else{
	            $xhr=['status'=>2,'msg'=>'暂未获取到订单'];
	        }
	    }   
        return json_encode($xhr);
    }

}