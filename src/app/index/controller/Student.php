<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 16:12
 */

namespace app\index\controller;

use think\Request;
use think\Session;
use think\Cookie;
use think\Config;
use think\Db;
use think\App;
use think\Exception;

class Student extends Base
{
    protected $rst,$school,$children,$class,$grade;
    public function _initialize()
    {
        //初始化继承的控制器
        parent::_initialize();
        $this->rst = Request::instance();
        $this->children = new \app\admin\model\Children();
        $this->school = new \app\admin\model\School();
        $this->class = new \app\admin\model\ClassRoom();
        $this->grade = new \app\admin\model\Grade();
    }
    //选择学校区域
    public function region()
    {
        return $this->fetch();
    }
    //选择学校
    public function school()
    {
        $name = $this->rst->param('name');
        if($name){
            $result = Db::name('school')->where('school_area',$name)->select();
        }else{
            return $this->redirect('/index/student/region');
        }
        
        $this->assign('res',$result);
        return $this->fetch("school");
    }
    //选择用餐学生
    public function meals()
    {
        $id = $this->rst->param('id');
        if(!$id){
            return $this->redirect('/index/student/region');
        }
        $sc = $this->school->getSchoolFind($id);
        $gr = Db::name('grade')->where('school_id',$id)->select();
        $cl = Db::name('class')->where('school_id',$id)->select();

        $this->assign('sc',$sc);
        $this->assign('gr',$gr);
        $this->assign('cl',$cl);
        return $this->fetch("meals");
    }
    //执行添加用餐学生
    public function mealsInsert()
    {
        $data = $this->rst->post();
        $data['user_id'] = Session::get('user_id');
        $isName = Db::name('children')->where(['user_id'=>$data['user_id'], 'children_name'=>
            $data['children_name'],'class_id'=>$data['class_id']])->find();
        if(!$isName){
            $res = $this->children->addChildrenInfo($data);
            if($res){
				
                $xhr = ['status'=>1,'msg'=>'添加成功'];
            }else{
                $xhr = ['status'=>0,'msg'=>'添加失败'];
            }
        }else{
            $xhr = ['status'=>2,'msg'=>'您已经添加该学生'];
        }
        return json_encode($xhr);
    }
}