<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 15:50
 */

namespace app\index\controller;

use think\Cookie;
use think\Request;
use think\Session;
use think\Config;
use think\Db;
use think\App;
use think\Exception;

class Order extends Base
{
    protected $rst,$children,$order,$user,$payback,$refund,$uid;
    public function _initialize()
    {
        //初始化继承的控制器
        parent::_initialize();
        //取出session的值
        if(Session::get('user_id')){
            $this->uid = Session::get('user_id');
        }
        $this->rst = Request::instance();
        $this->children = new \app\admin\model\Children();
        $this->order = new \app\admin\model\Order();
        $this->user = new \app\admin\model\User();
        $this->refund = new \app\admin\model\Refund();
        $this->payback = new \app\admin\model\Payback();
    }
    //我的订单列表
    public function myOrder()
    {
        $id = $this->rst->param('id');
        if(!$id){
            $this->redirect('/index/index/index');
            exit;
        }    
        $res = Db::name('order')->alias('a')
            ->join('menu b','a.menu_id=b.menu_id')
            ->where(['a.user_id'=>$this->uid,'a.children_id'=>$id])
            ->select();

        $this->assign('res',$res);
        return $this->fetch('Myorder');
    }
    //订单详细
    public function orderDetail()
    {
        $id = $this->rst->param('id');
    	if(!$id){
            $this->redirect('/index/index/index');
            exit;
        }      
        $res = Db::name('order')->alias('a')
            ->join('menu b','a.menu_id=b.menu_id')
            ->join('class c','a.class_id=c.class_id')
            ->join('school d','a.school_id=d.school_id')
            ->join('children e','a.children_id=e.children_id')
            ->field('a.*,b.menu_name,b.menu_img,b.menu_price,c.class_name,d.school_name,e.children_name')
            ->where('a.order_id',$id)
            ->find();

        $this->assign('res',$res);
        return $this->fetch('orderdetail');
    }
    //订单未支付详细
    public function orderNoPay()
    {
        $id = $this->rst->param('id');
         if(!$id){
            $this->redirect('/index/index/index');
            exit;
        }else if($id){
        	  $res = Db::name('order')->alias('a')
            ->join('menu b','a.menu_id=b.menu_id')
            ->join('class c','a.class_id=c.class_id')
            ->join('school d','a.school_id=d.school_id')
            ->join('children e','a.children_id=e.children_id')
            ->field('a.*,b.menu_name,b.menu_img,b.menu_price,c.class_name,d.school_name,e.children_name')
            ->where('a.order_id',$id)
            ->find();
            if(!$res){
	            $this->redirect('/index/index/');
	            exit;
            }
        }     
      

        $this->assign('res',$res);
        return $this->fetch('ordernopay');
    }
    //未支付订单删除
    public function orderNoPayDel(){
        $id = $this->rst->param('id');
        $res = $this->order->delOrderInfo($id);
        return $res;
    }

/*    //取消支付订单
    public function callOrder()
    {
        $id = $this->rst->param('id');
        $res = Db::name('order')->alias('a')
            ->join('menu b','a.menu_id=b.menu_id')
            ->join('class c','a.class_id=c.class_id')
            ->join('school d','a.school_id=d.school_id')
            ->join('children e','a.children_id=e.children_id')
            ->field('a.*,b.menu_name,b.menu_img,b.menu_price,c.class_name,d.school_name,e.children_name')
            ->where('a.order_id',$id)
            ->find();
        $this->assign('res',$res);
        return $this->fetch('callorder');
    }*/
    //我的退款记录
    public function myRefund()
    {
        $id = $this->rst->param('id');
        if(!$id){
            $this->redirect('/index/index/index');
            exit;
        }    
        $res = Db::name('refund')->where(['children_id'=>$id,'user_id'=>$this->uid])->select();
        $leres = Db::name('leave')->where(['children_id'=>$id,'user_id'=>$this->uid])->select();

        $this->assign('leres',$leres);
        $this->assign('res',$res);
        return $this->fetch('myRefund');
    }
    //是否存在订单
    public function isOrder()
    {
        $id = $this->rst->param('id');
        $isOrder = Db::name('order')->where(['children_id'=>$id,'user_id'=>$this->uid])->where('order_status','<>',0)->order('create_time desc')->find();
        $toDay = date('Y-m-d');
       // && $toDay>=$isOrder['start_date'] || $toDay<=$isOrder['end_date']
        if(!$isOrder){
            $xhr = ['status'=>0];
        }else{
            $xhr = ['status'=>1];
        }
        return $xhr;
    }
    //发起退款
    public function refund(){
        $id = $this->rst->param('id');
        $res = Db::name('order')->where(['children_id'=>$id,'user_id'=>$this->uid])->where('order_status','<>',0)->order('create_time desc')->find();
		 if(!$res){
            $this->redirect('/index/index/index');
            exit;
        } 
        $res['end_dates'] = date('Y-m-d', strtotime('+1 day'));
        $res['start_dates'] = date('Y-m-d');

        $this->assign('res',$res);
        return $this->fetch();
    }
    //退款发起
    public function refundInsert(){
        $data = $this->rst->post();
        $data['user_id'] = $this->uid;
        $isDay = Db::name('refund')->where(['start_date'=>$data['start_date'],'end_date'=>
            $data['end_date'],'user_id'=>$this->uid,'children_id'=>$data['children_id']])->find();
        //查询相应的订单
        $info = Db::name('order')
                ->alias('a')
                ->join('menu b','a.menu_id=b.menu_id')
                ->where(['children_id'=>$data['children_id'],'user_id'=>$this->uid])
                ->field('a.*,b.menu_price')
                ->order('create_time desc')
                ->find();
          $isMoney = Db::name('refund')->where(['trade_no'=>$info['trade_no'],'refund_status' => 2])->sum('refund_money');        
          $isMoneyTo = Db::name('leave')->where(['trade_no'=>$info['trade_no'],'leave_status' => 2])->sum('refund_money');        
    	if($isMoney+$isMoneyTo >= $info['pay_money']){
    		 $xhr = ['status'=>3,'msg'=>'该订单已经全部退款'];
    	}else{
    		if(!$isDay){
	            //查询相对应购买套餐的价格
	            $data['user_id'] = $this->uid;
	            $data['trade_no'] = $info['trade_no'];
	            $data['refund_no'] = getRefundNo();
	            $data['menu_id'] = $info['menu_id'];
	            $data['create_time'] = time();
	            $data['pay_money'] = $info['pay_money'];
	            $data['refund_money'] = $info['menu_price']*$data['refund_day'];
	            
	            $res = $this->refund->addRefundInfo($data);
	            if($res){
	                $xhr = ['status'=>1,'msg'=>'提交成功,请等待审核'];
	            }else{
	                $xhr = ['status'=>0,'msg'=>'提交失败'];
	            }
	        }else{
	            $xhr = ['status'=>2,'msg'=>'该日期段已提交退款'];
	        }
    	}
            
    
        return json_encode($xhr);
    }
    //补缴记录
    public function payBack()
    {
        $uid = Session::get('user_id');
        $id = $this->rst->param('id');
        if(!$id){
            $this->redirect('/index/index/index');
            exit;
        }    
        $res = Db::name('payback')->alias('a')
                ->join('menu b','a.menu_id=b.menu_id')
                ->where(['a.user_id'=>$uid,'a.children_id'=>$id])
                ->select();

        $this->assign('res',$res);
        return $this->fetch('Payback');
    }

}