<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 10:46
 */

namespace app\admin\model;

use think\Model;

class Order extends Model
{
    protected $pk = 'order_id',$order;
    //获取单条信息
    public function getOrderFind($id)
    {
        return $this->get($id);
    }
    //获取所有列表
    public function getOrderList()
    {
        $res = $this->order($this->pk,'desc')
            ->select();
        return $res;
    }
    //获取分页列表
    public function getOrderPage($where = [],$psize)
    {
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->paginate($psize);
        return $res;
    }
    //添加信息
    public function addOrderInfo($data = [])
    {
        $res = $this->allowField(true)
            ->save($data);
        return $res;
    }
    //删除单条信息
    public function delOrderInfo($id)
    {
        $res = $this::destroy($id);
        return $res;
    }
    //修改指定信息
    public function upOrderInfo($id,$data = [])
    {
        $res = $this->allowField(true)
            ->where($this->pk,$id)
            ->update($data);
        return $res;
    }
    //多条件查询
    public function getWhereOrder($where =[]){
        $res = $this->where($where)
            ->order($this->pk,'desc')
            ->find();
        return $res;
    }
}