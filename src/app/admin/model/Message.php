<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 10:46
 */

namespace app\admin\model;

use think\Model;
use think\Session;
use think\Cookie;
use think\Db;
use think\Request;

class Message extends Model
{
    protected $pk = 'message_id',$Message;
    //获取单条信息
    public function getMessageFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getMessageList($limitRows = 10)
    {
        $res = $this->limit($limitRows)
            ->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getMessagePage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addMessageInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delMessageInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upMessageInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
}