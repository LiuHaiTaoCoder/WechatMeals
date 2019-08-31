<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/2
 * Time: 15:37
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Config;
use think\Db;
use think\Cookie;
use think\Exception;
use think\Session;

class School extends Controller
{
    protected $school,$grade,$rst;
    public function _initialize()
    {
        $this->page = new \stdClass();
        $this->rst = Request::instance();
        $this->school = new \app\admin\model\School();
        $this->grade = new \app\admin\model\Grade();
    }

    //学校管理页面
    public function school($where=[],$name = '',$start='',$end='')
    {
        if($name){
            $name != '' ? $where['school_name'] = ['like','%'.$name.'%'] : '';
        }
        if($start || $end) {
            $start = strtotime($start."00:00:00");
            $end = strtotime($end."23:59:59");
            $where['create_time'] = ['between time', [$start, $end]];
        }
        $res = $this->school->getSchoolPage($where,10);
        foreach($res as $k => $v){
            $v['address'] = $v['school_province'].$v['school_city'].$v['school_area'].$v['school_address'];
            $v['count'] = Db::name('order')->where('school_id',$v['school_id'])->count();
        }
        $page = $res->render();

        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //学校管理添加页面
    public function schoolAdd()
    {
        return $this->fetch('school-add');
    }
    //执行学校管理添加
    public function schoolInsert()
    {
        $data = $this->rst->post();
        $isName = Db::name('school')->where('school_name',$data['school_name'])->find();
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $data['create_time'] = time();
            $res = $this->school->addSchoolInfo($data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //学校管理删除
    public function schoolDel()
    {
        $id = $this->rst->param('id');
        $res = $this->school->delSchoolInfo($id);
        return $res;
    }
    //学校管理修改页面
    public function schoolEdit()
    {
        $id = $this->rst->param('id');
        $res =  $this->school->getSchoolFind($id);
        $this->assign('res',$res);
        return $this->fetch('school-edit');
    }
    //执行学校管理修改
    public function schoolUpdate()
    {
        $data = $this->rst->post();
        $isName = Db::name('school')->where('school_name',$data['school_name'])->where('school_id','neq',$data['school_id'])->find();
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $id = $data['school_id'];
            $res = $this->school->upSchoolInfo($id,$data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //学校管理状态
    public function schoolStatus()
    {
        $id = $this->rst->param('id');
        $data['school_status'] = $this->rst->get('status');
        $res = $this->school->upSchoolInfo($id,$data);
        return $res;
    }

    //年级管理
    public function grade($where = [],$name="")
    {
        if($name){
            $name != '' ? $where['grade_name'] = ['like','%'.$name.'%']  : '';
        }
        $res = Db::name('grade')
            ->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->where($where)
            ->order('a.school_id desc')
            ->field('a.*,b.school_name,b.school_province,b.school_city,b.school_area,b.school_address,b.school_cate')
            ->paginate(10);
        $res1 = array();
        foreach($res as $k => $v){
            $v['count'] = Db::name('class')->where('grade_id',$v['grade_id'])->count();
            array_push($res1,$v);
        }
        $page = $res->render();

        $res = $res1;
        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //年级添加
    public function gradeAdd()
    {
        $cate = $this->school->getSchoolList();
        $this->assign('cate',$cate);
        return $this->fetch('grade-add');
    }
    //执行年级添加
    public function gradeInsert()
    {
        $data = $this->rst->post();
        $isName = Db::name('grade')->where(['grade_name'=>$data['grade_name'],'school_id'=>$data['school_id']])->find();
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $res = $this->grade->addGradeInfo($data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //年级修改
    public function gradeEdit()
    {
        $id = $this->rst->param('id');
        $ids = $this->rst->param('ids');
        $cate = Db::name('school')->where('school_id','neq',$ids)->select();
        $res =  Db::name('grade')->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->field('a.*,b.school_name')
            ->where('grade_id',$id)
            ->find();

        $this->assign('cate',$cate);
        $this->assign('res',$res);
        return $this->fetch('grade-edit');
    }
    //执行年级修改
    public function gradeUpdate()
    {
        $data = $this->rst->post();
        $id = $data['grade_id'];
        $isName = Db::name('grade')->where('grade_name',$data['grade_name'])->where('grade_id','neq',$id)->find();
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $data['update_time'] = time();
            $res = $this->grade->upGradeInfo($id,$data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //执行年级删除
    public function gradeDel()
    {
        $id = $this->rst->param('id');
        $res = $this->grade->delGradeInfo($id);
        return $res;
    }

    /*
    public function schoolCate(){
        return $this->fetch('schoolCate');
    }
    //学校分类添加页面
    public function schoolCateAdd()
    {
        return $this->fetch('schoolCate-add');
    }
    //执行学校分类添加
    public function schoolCateInsert()
    {

    }
    //学校分类删除
    public function schoolCateDel()
    {

    }
    //学校分类修改页面
    public function schoolCateEdit()
    {
        return $this->fetch('schoolCate-edit');
    }
    //执行学校分类修改
    public function schoolCateUpdate()
    {

    }*/

}