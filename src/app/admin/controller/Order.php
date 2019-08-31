<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/2
 * Time: 15:36
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Config;
use think\Db;
use think\Cookie;
use think\Exception;
use think\Session;

class Order extends Common
{
    protected $rst,$order,$refund;
    public function _initialize()
    {
        $this->rst = Request::instance();
        $this->order = new \app\admin\model\Order();
        $this->refund = new \app\admin\model\Refund();
    }
    //订单列表
    public function orderList($where = [],$start = '',$end = '',$order='')
    {
        if($order){
            $order != '' ? $where['a.trade_no'] = ['like','%'.$order.'%'] : '';
        }
        if($start || $end){
            $start = strtotime($start.'00:00:00');
            $end = strtotime($end.'23:59:59');
            $where['a.create_time'] = ['between time',[$start,$end]];
        }
        $res = Db::name('order')->alias('a')
            ->join('user b','a.user_id=b.user_id')
            ->join('children c','a.children_id=c.children_id')
            ->join('school d','a.school_id=d.school_id')
            ->join('class e','a.class_id=e.class_id')
            ->join('menu f','a.menu_id=f.menu_id')
            ->field('a.*,b.user_name,c.children_name,d.school_name,e.class_name,f.menu_name')
            ->where($where)
            ->order('a.order_id desc')
            ->paginate(10);
        $page = $res->render();

        $this->assign('res',$res);
        $this->assign('page',$page);
        return $this->fetch('order-list');
    }
    //订单删除
    public function orderDel()
    {
         $id = $this->rst->param('id');
         $res = $this->order->delOrderInfo($id);
         return $res;
    }
    //执行确认收货程序
    public function confirmReceipt()
    {
        $id = $this->rst->param('id');
        $data['order_status'] = $this->rst->get('status');
        $res = $this->order->upOrderInfo($id,$data);
        return $res;
    }
    //计划任务脚本执行
    public function checkReceipt()
    {
        //待收货的状态
        $where['order_status'] = 1;
        $whs['payback_status'] = 1;
        $curr_date = strtotime(date('Y-m-d'));
        $order = Db::name('order')->where($where)->select();
        $payback = Db::name('payback')->where($whs)->select();
        if($order || $payback){
            //将查询的数据遍历出来
            foreach($order as $k => $v) {
                $v['end_date'] = strtotime($v['end_date']);
                if($curr_date > $v['end_date']){
                    Db::name('order')->where($where)->setField('order_status',2);
                    Db::name('payback')->where($whs)->setField('payback_status',2);
                }else{
                    exit;
                }
            }
        }else{
            return ;
        }

    }
    //打印订单报表
    public function orderPrint(){

    }
    //导出订单报表
    public function orderExport(){
        $xlsData = Db::name('order')->alias('a')
            ->join('user b','a.user_id=b.user_id')
            ->join('children c','a.children_id=c.children_id')
            ->join('school d','a.school_id=d.school_id')
            ->join('class e','a.class_id=e.class_id')
            ->join('menu f','a.menu_id=f.menu_id')
            ->field('a.*,b.user_name,c.children_name,d.school_name,e.class_name,f.menu_name')
            ->select();
        Vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //设置Excel文档属性
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I,J,K");
        $arrHeader = array('订单编号','家长名','学生名','学校','班级','购买套餐','购买周期','配送天数','支付金额','订单状态','下单时间');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //设置单元格格式s
        $objExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode("0"); 
         foreach($xlsData as $k => $v){
            $v['real_day'] = $v['real_day'].'天';
            $v['pay_money'] = $v['pay_money'].'';
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            $k +=2;
            switch ($v['order_status']){
                case 0:
                    $v['order_status'] = '未支付';
                    break;
                case 1:
                	$v['order_status'] = '已支付';
                    break;
                case 2:
                    $v['order_status'] = '已完成';
                    break;
            }

            $objActSheet->setCellValue('A'.$k, $v['trade_no']);
            $objActSheet->setCellValue('B'.$k, $v['user_name']);
            $objActSheet->setCellValue('C'.$k, $v['children_name']);
            $objActSheet->setCellValue('D'.$k, $v['school_name']);
            $objActSheet->setCellValue('E'.$k, $v['class_name']);
            $objActSheet->setCellValue('F'.$k, $v['menu_name']);
            $objActSheet->setCellValue('G'.$k, $v['buy_cycle']);
            $objActSheet->setCellValue('H'.$k, $v['real_day']);
            $objActSheet->setCellValue('I'.$k, $v['pay_money']);
            $objActSheet->setCellValue('J'.$k, $v['order_status']);
            $objActSheet->setCellValue('K'.$k, $v['create_time']);
            
           $objActSheet->getRowDimension($k)->setRowHeight(15);
        }
        $width = array(15,10,20,25,30);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth($width[2]);
        $objActSheet->getColumnDimension('B')->setWidth($width[1]);
        $objActSheet->getColumnDimension('C')->setWidth($width[1]);
        $objActSheet->getColumnDimension('D')->setWidth($width[3]);
        $objActSheet->getColumnDimension('E')->setWidth($width[2]);
        $objActSheet->getColumnDimension('F')->setWidth($width[0]);
        $objActSheet->getColumnDimension('G')->setWidth($width[1]);
        $objActSheet->getColumnDimension('H')->setWidth($width[1]);
        $objActSheet->getColumnDimension('I')->setWidth($width[1]);
        $objActSheet->getColumnDimension('J')->setWidth($width[1]);
        $objActSheet->getColumnDimension('K')->setWidth($width[2]);

        $outfile = "订单信息表.xlsx";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
        exit;
    }

    //退款处理
    public function refund($where = [],$start ='',$end = '',$order='')
    {
        if(!empty($order)){
            $order != '' ? $where['a.trade_no'] = ['like','%'.$order.'%']  : '';
        }
        if($start || $end){
            $start = strtotime($start.'00:00:00');
            $end = strtotime($end.'23:59:59');
            $where['create_time'] = ['between time',[$start,$end]];
        }
        $res = Db::name('refund')
            ->alias('a')
            ->join('user b','a.user_id=b.user_id')
            ->join('menu c','a.menu_id=c.menu_id')
            ->order('a.refund_id desc')
            ->field('a.*,b.user_name,c.menu_name')
            ->where($where)
            ->order('a.create_time desc')
            ->paginate(10);
        $page = $res->render();

        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch('refund');
    }
    //退款申请修改
    public function refundEdit()
    {
        return $this->fetch('refund-edit');
    }
    //执行退款申请修改
    public function refundUpdate()
    {
        $id = $this->rst->param('id');
        $data['refund_status'] = $this->rst->param('status');
        $res = $this->refund->upRefundInfo($id,$data);
        return $res;
    }
    //退款申请删除
    public function refundDel()
    {
        $id = $this->rst->param('id');
        $res = $this->refund->delRefundInfo($id);
        return $res;
    }
}