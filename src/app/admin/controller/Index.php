<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/1
 * Time: 11:49
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Console;
use think\Db;
use think\Cookie;
use think\Exception;
use think\Session;

class Index extends Common
{
    protected $rst,$admin;
    //实例化操作
    public function _initialize()
    {
        $this->rst = Request::instance();
        $this->admin = new \app\admin\model\Index();
    }
    //后台首页左侧导航
    public function index()
    {
        if(!Cookie::has('admin_login')){
            Cookie::delete('admin_login');
            $this->redirect('/admin/login/index');
            exit;
        }
        $this->assign('admin_login',Cookie::get('admin_login'));
        return $this->fetch();
    }
    //后台欢迎页面
    public function welcome()
    {
        $sess = Session::get('admin_id');
        $res = Db::name('admin_log')->where(['admin_id'=>$sess['admin_id']])->order('log_id','desc')->find();
        $res1 = Db::name('order')->count();
        $res2 = Db::name('user')->count();
        $res3 = Db::name('message')->count();
        $res4 = Db::name('order')->where('order_status','<>',0)->sum('pay_money');


        $this->assign('admin_login',Cookie::get('admin_login'));
        $this->assign('res',$res);
        $this->assign('countOrder',$res1);
        $this->assign('countUser',$res2);
        $this->assign('countFeedBack',$res3);
        $this->assign('countMoney',$res4);
        return $this->fetch();
    }
    //后台欢迎页面1
    public function welcome1()
    {
        return $this->fetch();
    }
    //分类(用于测试)
    public function cate()
    {
        return $this->fetch();
    }
    //管理员列表页面
    public function adminList($where = [],$admin_name = '',$start = '',$end = '')
    {
        if($admin_name){
            $admin_name != '' ? $where['admin_name'] = ['like','%'.$admin_name.'%'] : '';
        }
        if($start || $end) {
            $start = strtotime($start."00:00:00");
            $end = strtotime($end."23:59:59");
            $where['create_time'] = ['between time',[$start, $end]];
        }
        $res = Db::name('admin')->where($where)->order('admin_id','desc')->paginate(10);
        $page = $res->render();
        $this->assign('list',$res);
        $this->assign('page',$page);
        return $this->fetch('admin-list');
    }
    //管理员添加页面
    public function adminAdd()
    {
        return $this->fetch('admin-add');
    }
    //执行管理员添加
    public function adminInsert($arr = [])
    {
        $xhr = [];
        $data = $this->rst->post();
        $isName = Db::name('admin')->where('admin_name',$data['admin_name'])->find();
        if(!empty($isName)) {
            $xhr = ['status'=>'2'];
            return $xhr;
            exit;
        }else{
            $data['create_time'] = time();
            $data['admin_pwd'] = md5(sha1($data['admin_pwd']));
            $res =  $this->admin->addAdminInfo($data);
            if($res) {
                $xhr = ['status'=>'1'];
            }else {
                $xhr = ['status'=>'0'];
            }
            return $xhr;
        }
    }
    //管理员删除
    public function adminDel()
    {
        $admin_id = $this->rst->param('id');
        $res = $this->admin->delAdminInfo($admin_id);
        return $res;
    }
    //管理员修改页面
    public function adminEdit()
    {
        $admin_id = $this->rst->param('id');
        $res =  Db::name('admin')->where('admin_id',$admin_id)->find();
        $this->assign('res',$res);
        return $this->fetch('admin-edit');
    }
    //执行管理员修改
    public function adminUpdate($xhr = [],$newData=[])
    {
    	$data = $this->rst->post();
        $oldpwd = $data['oldpwd'];
        if(!empty($oldpwd)){
            $oldpwd = md5(sha1($data['oldpwd']));
            $isPass = Db::name('admin')->where(['admin_id'=>$data['admin_id'],'admin_pwd'=>$oldpwd])->find();
            $isName = $this->admin->isNameExists($data['admin_name'],$data['admin_id']);
            if(empty($isPass)){
                $xhr = ['status'=>2];
                return $xhr;
                exit;
            }else if($isName){
                $xhr = ['status'=>3];
                return $xhr;
                exit;
            }else if(!$isName){
                $newData['admin_name'] = $data['admin_name'];
                $newData['admin_pwd'] = md5(sha1($data['admin_pwd']));
                $newData['admin_role'] = $data['admin_role'];
                $res = $this->admin->upAdminInfo($data['admin_id'],$newData);
                if($res)
                    $xhr = ['status'=>1];
                else
                    $xhr = ['status'=>0];
                return $xhr;
            }
        }else{
            $isName = $this->admin->isNameExists($data['admin_name'],$data['admin_id']);
            if(!$isName){
                $newData['admin_name'] = $data['admin_name'];
                $newData['admin_role'] = $data['admin_role'];
                $res = $this->admin->upAdminInfo($data['admin_id'],$newData);
                if($res)
                    $xhr = ['status'=>1];
                else
                    $xhr = ['status'=>0];
                return $xhr;
            }
        }
    }
    //管理员启用停用
    public function adminStatus()
    {
        $admin_id = $this->rst->param('id');
        $data['admin_status'] = $this->rst->get('admin_status');
        $res = $this->admin->upAdminInfo($admin_id,$data);
        return $res;
    }
    //清除runtime下的文件从而不删除runtime目录
    public function clearRuntime()
    {
	   	if(delete_dir_file(RUNTIME_PATH)){
    		return json_encode(['code'=>'1','msg'=>'缓存清除成功']);
    	}else{
    		return json_encode(['code'=>'0','msg'=>'缓存清除失败']);
    	}
    	
    }

    //管理员角色
    public function adminRole()
    {
        return $this->fetch('admin-role');
    }
    //管理员分类
    public function adminCate()
    {
        return $this->fetch('admin-cate');
    }
    //管理员权限规则
    public function adminrule()
    {
        return $this->fetch('admin-rule');
    }
    //加载并引导CSS与JS文件
    public function adminFile()
    {
        return $this->fetch();
    }
    //报错404时调用此函数
    public function errors()
    {
        return $this->fetch('error');
    }

}