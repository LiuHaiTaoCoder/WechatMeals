<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/2
 * Time: 15:57
 */

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Console;
use think\Config;
use think\Db;
use think\Cookie;
use think\Exception;
use think\Session;
use traits\think\Instance;

class Message extends Controller
{
    protected $rst,$message;
    public function _initialize()
    {
        $this->rst = Request::instance();
        $this->message = new \app\admin\model\Message();
    }

    public function message($where=[],$name = "")
    {
        if($name){
            $name != '' ? $where['b.user_name'] = ['like','%'.$name.'%']  : '';
        }
        $res = Db::name('message')
            ->alias('a')
            ->join('user b','a.user_id=b.user_id')
            ->where($where)
            ->field('a.*,b.user_name')
            ->order('a.message_id desc')
            ->paginate(10);
        $page = $res->render();
        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //意见反馈添加页面
    public function messageAdd()
    {
        return $this->fetch('message-add');
    }
    //执行意见反馈添加
    public function messageInsert()
    {
        $data = $this->rst->get();
        $res = $this->message->addMessageInfo($data);
        if($res) {
            $xhr = ['status'=>'1'];
        }else{
            $xhr = ['status'=>'0'];
        }
        return $xhr;
    }
    //意见反馈删除
    public function messageDel()
    {
        $id = $this->rst->param('id');
        $res = $this->message->delMessageInfo($id);
        return $res;
    }
    //意见反馈修改页面
    public function messageEdit()
    {
        $id = $this->rst->param('id');
        $res =  $this->message->getMessageFind($id);
        $this->assign('res',$res);
        return $this->fetch('message-edit');
    }
    //执行意见反馈修改
    public function messageUpdate()
    {
        $id = $this->rst->param('id');
        $data['message_img'] = $this->rst->get('message_img');
        $data['message_content'] = $this->rst->get('message_content');
        $res = $this->message->upMessageInfo($id,$data);
        if($res){
            $xhr = ['status'=>1];
        }else{
            $xhr = ['status'=>0];
        }
        return $xhr;
    }
    public function messageStatus()
    {
        $id = $this->rst->param('id');
        $data['message_status'] = $this->rst->get('message_status');
        $res = $this->message->upMessageInfo($id,$data);
        return $res;
    }
}