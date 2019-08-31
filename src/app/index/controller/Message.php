<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 16:07
 */
namespace app\index\controller;

use think\Request;
use think\Session;
use think\Config;
use think\Db;
use think\App;
use think\Exception;

class Message extends Base
{
    protected $rst,$feedback;
    public function _initialize()
    {
        //初始化继承的控制器
        parent::_initialize();
        $this->rst = Request::instance();
        $this->feedback = new \app\admin\model\Message();
    }
    //反馈留言
    public function feedBack()
    {
        return $this->fetch('feedback');
    }
    //提交留言
    public function feedBackInsert()
    {
        $data = $this->rst->get();
        $data['user_id'] = Session::get('user_id');
        $result = $this->feedback->addMessageInfo($data);
        return $result;
    }
}