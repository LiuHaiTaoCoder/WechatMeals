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
use think\Loader;

class User extends Common
{
    protected $rst;
    protected $user;
    protected $child;
    protected $class;
    protected $leave;
    protected $school;
    protected $grade;
    //加载内置函数库以及模型
    public function _initialize()
    {
        $this->rst = Request::instance();
        $this->user = new \app\admin\model\User();
        $this->child = new \app\admin\model\Children();
        $this->class = new \app\admin\model\ClassRoom();
        $this->leave = new \app\admin\model\Leave();
        $this->school = new \app\admin\model\School();
        $this->grade = new \app\admin\model\Grade();
    }
    //家长管理
    public function parents($where = [],$start='',$end='',$name='')
    {
        if($name){
            $name != '' ? $where['user_name'] = ['like','%'.$name.'%'] : '' ;
        }
        if($start || $end){
            $start = strtotime($start.'00:00:00');
            $end =  strtotime($end.'23:59:59');
            $where['create_time'] =  ['between time', [$start,$end]];
        }
        $res = $this->user->getUserPage($where,10);
        foreach($res as $k => $v){
            $v['count'] = Db::name('order')->where(['user_id'=>$v['user_id'],'order_status'=>1])->sum('pay_money');
            $v['children'] = Db::name('children')->where('user_id',$v['user_id'])->count();
        }
        $page = $res->render();

        $this->assign('res',$res);
        $this->assign('page',$page);
        return $this->fetch();
    }
    //用户基本信息修改
    public function parentsEdit()
    {
        $id = $this->rst->param('id');
        $res = $this->user->getUserFind($id);
        $this->assign('res',$res);
        return $this->fetch('parents-edit');
    }
    //执行用户基本信息修改
    public function parentsUpdate()
    {
        $data = $this->rst->post();
        $id = $this->rst->post('user_id');
        $isName = $this->user->isNameExists($data['user_name'],$id);
        if($isName)
        {
            $xhr = ['status'=>2];
            return $xhr;
            exit;
        }else{
            $data['update_time'] = time();
            $res = $this->user->upUserInfo($id,$data);
            if($res){
                $xhr = ['status'=>1];
            }else{
                $xhr = ['status'=>0];
            }
            return $xhr;
        }
    }
    //用户密码修改
    public function parentsPassword()
    {
        $id = $this->rst->param('id');
        $res = $this->user->getUserFind($id);
        $this->assign('res',$res);
        return $this->fetch('parents-password');
    }
    //执行用户密码修改
    public function parentsUpPassword($newData=[])
    {
        $data = $this->rst->post();
        $oldpwd = md5(sha1($data['old_pwd']));
        $isPass = Db::name('user')->where(['user_id'=>$data['user_id'],'user_pwd'=>$oldpwd])->find();
        if(!empty($isPass)){
            $newData['user_pwd'] = md5(sha1($data['new_pwd']));
            $res = $this->user->upUserInfo($data['user_id'],$newData);
            if($res)
                $xhr = ['status'=>1];
            else
                $xhr = ['status'=>0];
        }else{
            $xhr = ['status'=>2];
        }
        return $xhr;
    }
    //执行用户删除
    public function parentsDel()
    {
        $id = $this->rst->param('id');
        $res = $this->user->delUserInfo($id);
        return $res;
    }
    //执行家长状态修改
    public function parentsStatus()
    {
        $id = $this->rst->param('id');
        $data['user_status'] = $this->rst->param('status');
        $res = $this->user->upUserInfo($id,$data);
        return $res;
    }

    //学生管理
    public function student($where=[],$name = '')
    {
        if($name){
            $name != '' ? $where['children_name'] = ['like','%'.$name.'%'] : '';
        }
        $res = Db::name('children')
            ->alias('a')
            ->join('user b','a.user_id=b.user_id')
            ->join('school c','a.school_id=c.school_id')
            ->join('class d','a.class_id=d.class_id')
            ->field('a.*,b.user_name,b.user_mobile,c.school_name,d.class_name')
            ->order('a.children_id desc')
            ->where($where)
            ->paginate(10);
        $page = $res->render();

        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //学生修改
    public function studentEdit()
    {
        $id = $this->rst->param('id');
        $ids = $this->rst->param('ids');
        $user_id = $this->rst->param('uid');

        $sb = Db::name('school')->where('school_id','neq',$ids)->select();
        $us = Db::name('user')->where('user_id','neq',$user_id)->select();

        $res =  Db::name('children')->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->join('user c','a.user_id=c.user_id')
            ->join('grade d','a.grade_id=d.grade_id')
            ->join('class e','a.class_id=e.class_id')
            ->field('a.*,b.school_name,c.user_name,d.grade_name,e.class_name')
            ->where('children_id',$id)
            ->find();


        $this->assign('user',$us);
        $this->assign('val',$sb);
        $this->assign('res',$res);
        return $this->fetch('student-edit');
    }
    //执行学生信息修改
    public function studentUpdate()
    {
        $data = $this->rst->post();
        $id = $this->rst->post('children_id');
        $isName = $this->child->isNameExists($data['children_name'],$id);
        if($isName)
        {
            $xhr = ['status'=>2];
            return $xhr;
            exit;
        }else{
            $data['update_time'] = time();
            $res = $this->child->upChildrenInfo($id,$data);
            if($res){
                $xhr = ['status'=>1];
            }else{
                $xhr = ['status'=>0];
            }
            return $xhr;
        }
    }
    //学生打印数据
    public function studentPrint(){
        $id = $this->rst->param('id');

        $ins = $this->class->getClassFind($id);
        $res =  Db::name('children')
            ->alias('a')
            ->join('user b','a.user_id=b.user_id')
            ->join('school c','a.school_id=c.school_id')
            ->join('class d','a.class_id=d.class_id')
            ->field('a.*,b.user_name,b.user_mobile,c.school_name,d.class_name')
            ->order('a.create_time desc')
            ->where('d.class_id',$id)
            ->select();
        $this->assign('info',$res);
        $this->assign('ins',$ins);
        return $this->fetch('print-s');
    }
    //学生添加
    public function studentAdd($school_id = '',$grade_id = '')
    {
        $res = $this->school->getSchoolList();
        $this->assign('res',$res);
        return $this->fetch('student-add');
    }
    //执行学生添加
    public function studentInsert()
    {
        $data = $this->rst->post();
        $isName = Db::name('children')->where('children_name',$data['children_name'])->find();
        if($isName)
        {
            $xhr = ['status'=>2];
            return $xhr;
            exit;
        }else{
            $res = $this->child->addChildrenInfo($data);
            if($res){
                $xhr = ['status'=>1];
            }else{
                $xhr = ['status'=>0];
            }
            return $xhr;
        }
    }
    //执行学生删除
    public function studentDel()
    {
        $id = $this->rst->param('id');
        $res  = $this->child->delChildrenInfo($id);
        return $res;
    }
    //导出学生报表
    public function exportStudent()
    {
        $xlsTitle = '学生报表';
        $res = Db::name('children')
            ->alias('a')
            ->join('user b','a.user_id=b.user_id')
            ->join('school c','a.school_id=c.school_id')
            ->join('class d','a.class_id=d.class_id')
            ->field('a.*,b.user_name,b.user_mobile,c.school_name,d.class_name')
            ->order('a.children_id desc')
            ->select();
        foreach($res as $k => $v){
            if($v['children_sex'] == 1){
                $res[$k]['children_sex'] = "男";
            }else{
                $res[$k]['children_sex'] = "女";
            }
        }

        $xlsCell  = array(
            array('children_id','ID'),
            array('children_name','姓名 '),
            array('children_sex','性别'),
            array('user_name','关联家长'),
            array('user_mobile','联系方式'),
            array('school_name','学校名称'),
            array('class_name','班级'),
            array('create_time','加入时间'),
        );
        $this->exportExcel($xlsTitle,$xlsCell,$res);
    }

    //班级管理
    public function classRoom($where = [],$name = '')
    {
        if($name){
            $name != '' ? $where['class_name'] = ['like','%'.$name.'%'] : '';
        }
        $res = Db::name('class')
            ->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->join('grade c','a.grade_id=c.grade_id')
            ->field('a.*,b.school_name,b.school_province,b.school_city,b.school_area,b.school_address,b.school_cate,c.grade_name')
            ->where($where)
            ->order('a.school_id asc')
            ->paginate(10);
        $res1=array();
        foreach($res as $k => &$v){
            $v['ctime'] = date('Y-m-d H:i:s',$v['create_time']);
            $v['number'] = Db::name('children')->where('class_id',$v['class_id'])->count();
            $v['address'] = $v['school_province'].$v['school_city'].$v['school_area'].$v['school_address'];
            array_push($res1,$v);
        }

        $page = $res->render();
        $res=$res1;
        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch('class');
    }
    //班级添加
    public function classAdd()
    {
        $school = $this->school->getSchoolList();
        $this->assign('cate',$school);
        return $this->fetch('class-add');
    }
    //查询指定的年级列表
    public function selectGrade(){
        $id = $this->rst->param('id');
        $res = Db::name('grade')->where('school_id',$id)->select();
        return $res;
    }
    //查询指定的班级
    public function selectClass(){
        $id = $this->rst->param('id');
        $res = Db::name('class')->where('grade_id',$id)->select();
        return $res;
    }

    //执行班级添加
    public function classInsert()
    {
       $data = $this->rst->post();
       $sid = $this->rst->post('school_id');
       // dump($data);
        $isName = Db::name('class')->where(['school_id'=>$sid,'class_name'=>$data['class_name']])->find();
        if($isName) {
            $xhr = ['status'=>2];
            return $xhr;
            exit;
        }else{
            $data['create_time'] = time();
            $res = $this->class->addClassInfo($data);
            if($res){
                $xhr = ['status'=>1];
            }else{
                $xhr = ['status'=>0];
            }
            return $xhr;
        }
    }
    //班级修改
    public function classEdit()
    {
        $id = $this->rst->param('id');
        $ids = $this->rst->param('ids');
        $cate = Db::name('school')->where('school_id','neq',$ids)->select();
        $res =  Db::name('class')->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->join('grade c','a.grade_id=c.grade_id')
            ->field('a.*,b.school_name,c.grade_name')
            ->where('class_id',$id)
            ->find();

        $this->assign('cate',$cate);
        $this->assign('res',$res);
        return $this->fetch('class-edit');
    }
    //执行班级修改
    public function classUpdate()
    {
        $data = $this->rst->post();
        $id = $this->rst->post('school_id');
        $class_name = $this->rst->post('class_name');
        $isName = $this->class->isNameExists($class_name,$id);
        if($isName)
        {
            $xhr = ['status'=>2];
            return $xhr;
            exit;
        }else {
            $data['update_time'] = time();
            $res = $this->class->upClassInfo($data['class_id'], $data);
            if ($res)
                $xhr = ['status' => 1];
            else
                $xhr = ['status' => 0];
            return $xhr;
        }
    }
    //执行班级删除
    public function classDel()
    {
        $id = $this->rst->param('id');
        $res = $this->class->delClassInfo($id);
        return $res;
    }
    //导出班级报表
    public function exportClass(){

        $xlsData = Db::name('class')
            ->alias('a')
            ->join('school b','a.school_id=b.school_id')
            ->join('grade c','a.grade_id=c.grade_id')
            ->field('a.*,b.school_name,b.school_province,b.school_city,b.school_area,b.school_address,b.school_cate,c.grade_name')
            ->select();
        $xlsTitle = '班级管理报表';
        $xlsCell  = array(
            array('class_id','ID'),
            array('class_name','班级名 '),
            array('number','班级人数'),
            array('school_name','学校名称'),
            array('address','地址'),
            array('ctime','加入时间'),
        );
        $res = array();
        foreach($xlsData as $k=>$v) {
            $v['ctime'] = date('Y-m-d H:i:s', $v['create_time']);
            $v['number'] = Db::name('children')->where('class_id', $v['class_id'])->count();
            $v['address'] = $v['school_province'] . $v['school_city'] . $v['school_area'] . $v['school_address'];
            array_push($res,$v);
        }
        $xlsData = $res;


        $this->exportExcel($xlsTitle,$xlsCell,$xlsData);
    }
    //请假列表
    public function leave($where = [],$name = '')
    {
        if($name){
            $name != '' ? $where['user_name'] = ['like','%'.$name.'%'] : '' ;
        }
        $res = $this->leave->getLeavePage($where,10);
        $page = $res->render();

        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //请假确认(确认之后将退款到原来的账户)
    public function leaveConfirm()
    {
        $id = $this->rst->param('id');
        $data['leave_status'] = $this->rst->get('status');
        $res = $this->leave->upLeaveInfo($id,$data);
        return $res;
    }
    //请假列表删除
    public function leaveDel(){
        $id = $this->rst->param('id');
        $res = $this->leave->delLeaveInfo($id);
        return $res;
    }

    //会员列表（测试）
    public function memberList()
    {
        return $this->fetch('member-list');
    }
    //会员列表1（测试）
    public function memberList1()
    {
        return $this->fetch('member-list1');
    }
    //城市联动测试
    public function city()
    {
        return $this->fetch();
    }

}