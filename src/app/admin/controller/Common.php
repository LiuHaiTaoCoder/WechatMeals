<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/10
 * Time: 16:05
 */

namespace app\admin\controller;

header("content-type:text/html;charset=gb2312");
use think\Controller;
use think\Db;
use think\Requeset;

class Common extends Controller
{
    //判断是否登录
    public function _initialize(){
        if(!cookie('admin_login')){
            cookie('admin_login',null);
            $this->redirect('/admin/login/index');
            exit;
        }
    }
    public function exportExcel($expTitle="",$expCellName="",$expTableData=""){
        $xlsTitle = iconv('utf-8','gb2312',$expTitle);//文件名称
        $fileName = $expTitle;//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    //导出班级报表
    public function exportClass(){
        $list = Db::name('class')
            ->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->join('grade c','a.grade_id=c.grade_id')
            ->field('a.*,b.school_name,b.school_province,b.school_city,b.school_area,b.school_address,b.school_cate,c.grade_name')
            ->select();
        Vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        Vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //设置Excel文档属性
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F");
        $arrHeader = array('ID','班级名','班级人数','所在学校','学校地址','加入时间');
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($list as $k=>$v){
            $v['ctime'] = date('Y-m-d H:i:s',$v['create_time']);
            $v['number'] = Db::name('children')->where('class_id',$v['class_id'])->count();
            $v['address'] = $v['school_province'].$v['school_city'].$v['school_area'].$v['school_address'];
            $k +=2;
            $objActSheet->setCellValue('A'.$k, $v['class_id']);
            $objActSheet->setCellValue('B'.$k, $v['class_name']);
            $objActSheet->setCellValue('C'.$k, $v['number']);
            $objActSheet->setCellValue('D'.$k, $v['school_name']);
            $objActSheet->setCellValue('E'.$k, $v['address']);
            $objActSheet->setCellValue('F'.$k, $v['ctime']);
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(15);
        }

        $width = array(15,5,20,25,30);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth($width[0]);
        $objActSheet->getColumnDimension('B')->setWidth($width[2]);
        $objActSheet->getColumnDimension('C')->setWidth($width[3]);
        $objActSheet->getColumnDimension('D')->setWidth($width[4]);
        $objActSheet->getColumnDimension('E')->setWidth($width[2]);
        $objActSheet->getColumnDimension('F')->setWidth($width[3]);

        $outfile = "班级信息表.xlsx";
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
}