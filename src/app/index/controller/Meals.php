<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 16:05
 */
namespace app\index\controller;

use think\Request;
use think\Session;
use think\Config;
use think\Db;
use think\App;
use think\Exception;


class Meals extends Base
{
    protected $rst,$user,$order,$menu,$uid,$lunar;
    public function _initialize()
    {
        //初始化继承的控制器
        parent::_initialize();
        //取出session的值
        if(Session::get('user_id')){
            $this->uid = Session::get('user_id');
        }
        $this->order = new \app\admin\model\Order();
        $this->menu = new \app\admin\model\Menu();
        $this->user = new \app\admin\model\User();
        $this->rst = Request::instance();
    }
    //我要订餐列表
    public function meals()
    {   
    	$id = $this->rst->param('id');
    	if(!$id){
    		$this->redirect('/index/index/index');
    	}
        $res = Db::name('children ')->alias('a')
            ->join('class c','a.class_id=c.class_id')
            ->join('school d','a.school_id=d.school_id')
            ->field('a.*,c.class_name,d.school_name')
            ->where('children_id',$id)
            ->find();
        $info = Db::name('menu')->order('menu_id asc')->select();
        $res['currdate'] = strtotime(date('Y-m-d'));
        $p_date = strtotime('2019-09-02');
        if($res['currdate'] > $p_date){
            $currDate = $res['currdate'];
        }else{
            $currDate = $p_date;
        }
        $this->assign('curr_date',$currDate);
        $this->assign('res',$res);
        $this->assign('info',$info);
        return $this->fetch('orderlist');
    }
    //提交订单给微信支付api
    public function orderSub()
    {
       $data = $this->rst->post();
       $isOrder = Db::name('order')
        			->where(['user_id'=>$this->uid,'children_id'=>$data['children_id']])
        			->where('order_status',1)
        			->find();
        //判断是否已经有订单
        if(empty($isOrder)){
            $data['trade_no'] = getOrderNo();
            $data['user_id'] = $this->uid;
            $data['create_time'] = time();
            $result = $this->order->addOrderInfo($data);
            //超过9-2之后就是餐费补缴
        	$currDate = strtotime(date('Y-m-d'));
            $ifDate = strtotime('2019-08-02');
            if($currDate > $ifDate){
                $nData['create_time'] = time();
                $nData['children_id'] = $data['children_id'];
                $nData['menu_id'] = $data['menu_id'];
                $nData['payback_money'] = $data['pay_money'];
                $nData['payback_day'] = $data['real_day'];
                $nData['payback_cycle'] = $data['buy_cycle'];
                $nData['menu_id'] = $data['menu_id'];
                $nData['user_id'] = $this->uid;
                $nData['trade_no'] = $data['trade_no'];

                $payback = Db::name('payback')->insert($nData);
                if($result && $payback){
                    Session::set('order',$data);
                    $xhr = ['status'=>1,'url'=>'/api/Wxpay/pay'];
                }else{
                    $xhr = ['status'=>0,'msg'=>'订单创建失败'];
                }
            }else{
                if($result){
                    Session::set('order',$data);
                    $xhr = ['status'=>1,'url'=>'/api/Wxpay/pay'];
                }else{
                    $xhr = ['status'=>0,'msg'=>'订单创建失败'];
                }
            }

        }else{
        	 $xhr = ['status'=>2,'msg'=>'该孩子已经定过餐了'];
        }
        return $xhr;
    }

}