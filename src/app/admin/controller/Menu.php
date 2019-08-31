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

class Menu extends Controller
{
    protected $menu,$food,$rst;
    public function _initialize()
    {
        $this->rst = Request::instance();
        $this->menu = new \app\admin\model\Menu();
        $this->food = new \app\admin\model\Food();
    }

    //套餐分类
    public function menu($where=[],$name='',$start='',$end='')
    {
        if($name){
            $name != '' ? $where['menu_name'] = ['like','%'.$name.'%'] : '';
        }
        if($start || $end) {
            $start = strtotime($start."00:00:00");
            $end = strtotime($end."23:59:59");
            $where['create_time'] = ['between time', [$start, $end]];
        }
        $res = $this->menu->getMenuPage($where,10);
        $page = $res->render();

        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //套餐分类增加
    public function menuAdd()
    {
        return $this->fetch('menu-add');
    }
    //执行套餐分类增加
    public function menuInsert()
    {
        $data = $this->rst->post();
        $isName = Db::name('menu')->where('menu_name',$data['menu_name'])->find();
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $data['create_time'] = time();
            $res = $this->menu->addMenuInfo($data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //套餐分类修改
    public function menuEdit()
    {
        $id = $this->rst->param('id');
        $res = $this->menu->getMenuFind($id);
        $this->assign('res',$res);
        return $this->fetch('menu-edit');
    }
    //执行套餐分类修改
    public function menuUpdate()
    {
        $data = $this->rst->post();
        $id = $this->rst->post('menu_id');
        $isName =  $this->menu->isNameExists($data['menu_name'],$id);
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $data['update_time'] = time();
            $res = $this->menu->upMenuInfo($id,$data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //执行套餐分类删除
    public function menuDel()
    {
        $id = $this->rst->param('id');
        $res = $this->menu->delMenuInfo($id);
        return $res;
    }
    //执行套餐状态修改
    public function menuStatus()
    {
        $data['menu_status'] = $this->rst->get('status');
        $id = $this->rst->param('id');
        $res = $this->menu->upMenuInfo($id,$data);
        return $res;
    }

    //套餐菜品管理
    public function food($where = [],$name='',$start='',$end='')
    {
        if($name){
            $name != '' ? $where['food_name'] = ['like','%'.$name.'%'] : '';
        }
        if($start || $end) {
            $start = strtotime($start."00:00:00");
            $end = strtotime($end."23:59:59");
            $where['create_time'] = ['between time', [$start, $end]];
        }
        $res = Db::name('food')->alias('a')
                ->join('menu b','a.menu_id=b.menu_id')
                ->field('a.*,b.menu_name')
                ->where($where)
                ->order('a.menu_id asc')
                ->paginate(10);
        $page = $res->render();

        $this->assign('page',$page);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //套餐菜品增加
    public function foodAdd()
    {
        $cate = $this->menu->getMenuList();
        $this->assign('cate',$cate);
        return $this->fetch('food-add');
    }
    //执行套餐菜品增加
    public function foodInsert()
    {
        $data = $this->rst->post();
        $id = $this->rst->post('menu_id');
        $isName = Db::name('food')->where(['menu_id'=>$id,'food_name'=>$data['food_name']])->find();
        if($isName){
            $xhr = ['status'=>2];
            return $xhr;
            exit;
        }else{
            $data['create_time'] = time();
            $res = $this->food->addFoodInfo($data);
            if($res){
                $xhr = ['status'=>1];
            }else{
                $xhr = ['status'=>0];
            }
            return $xhr;
        }
    }
    //套餐菜品修改
    public function foodEdit()
    {
        $id = $this->rst->param('id');
        $menuid = $this->rst->param('menuid');
        $cate = Db::name('menu')->where('menu_id','neq',$menuid)->select();
        $res =  Db::name('food')->alias('a')
            ->join('menu b','a.menu_id=b.menu_id')
            ->field('a.*,b.menu_name')
            ->where('food_id',$id)
            ->find();
        $this->assign('res',$res);
        $this->assign('cate',$cate);
        return $this->fetch('food-edit');
    }
    //执行套餐菜品修改
    public function foodUpdate()
    {
        $data = $this->rst->post();
        $ids = $this->rst->post('menu_id');
        $id = $this->rst->post('food_id');
        $isName = $this->food->isNameExists($data['food_name'],$ids,$id);
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $data['update_time'] = time();
            $res = $this->food->upFoodInfo($data['food_id'],$data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //执行套餐菜品删除
    public function foodDel()
    {
        $id = $this->rst->param('id');
        $res = $this->food->delFoodInfo($id);
        return $res;
    }
    public function foodStatus(){
        $data['food_status'] = $this->rst->get('status');
        $id = $this->rst->param('id');
        $res = $this->food->upFoodInfo($id,$data);
        return $res;
    }
}