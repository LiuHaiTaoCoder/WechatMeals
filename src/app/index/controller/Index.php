<?php
/*
 * author : Administrator
 * */
namespace app\index\controller;

use think\Request;
use think\Session;
use think\Cookie;
use think\Config;
use think\Db;
use think\App;
use think\Exception;

class Index extends Base
{
    protected $rst,$user,$children;
    public function _initialize()
    {
        //初始化继承的控制器
        parent::_initialize();
        $this->rst = Request::instance();
        $this->user = new \app\admin\model\User();
        $this->children = new \app\admin\model\Children();
    }

    public function index()
    {
        $id = Session::get('user_id');
        $count = Db::name('children')->where('user_id', $id)->count();
        if ($count) {
            $info = $this->user->getUserFind($id);
            $result = Db::name('children')->where(['user_id' => $id])->select();

            $this->assign('result', $result);
            $this->assign('user', $info);
            $this->assign('count', $count);
            return $this->fetch('personal');
        } else {
            $this->redirect('/index/student/region');
        }
    }
    public function ContactTheCustomerService()
    {
        return $this->fetch('ContactTheCustomerService');
    }
    //导入通用文件
    public function commonFile()
    {
        return $this->fetch('commonFile');
    }
}
