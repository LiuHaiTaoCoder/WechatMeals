<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/9
 * Time: 8:55
 */

namespace app\api\controller;

use think\Request;
use think\Controller;
use think\Db;

class Upload extends Controller
{
    protected $request;
    public function _initialize()
    {
        $this->request = Request::instance();
    }
    public function form()
    {
        $imgpath = $this->request->param();
        //获取上传过来的文件
        $file = request()->file('file');
        //移动到指定目录
        $info = $file->validate(['size'=>1024*1024*5,'ext'=>'jpg,jpeg,png,gif,bmp'])->move(ROOT_PATH . 'public' .DS. 'upload' .DS. 'admin',true,false);
        if($info)
        {
            $filepath = $info->getSaveName();
            $filepath = '/upload/admin/'.str_replace('\\','/',$filepath);
            $result = [
                'code' => '200',
                'filename' => $filepath
            ];
        }else{
            $result = [
                'code' => '404',
                'msg' => $info->getError(),
            ];
        }
        return json_encode($result);
    }
    public function formIndex()
    {
        $imgpath = $this->request->param();
        //获取上传过来的文件
        $file = request()->file('file');
        //移动到指定目录
        $info = $file->validate(['size'=>1024*1024*5,'ext'=>'jpg,jpeg,png,gif,bmp'])->move(ROOT_PATH . 'public' .DS. 'upload' .DS. 'index',true,false);
        if($info)
        {
            $filepath = $info->getSaveName();
            $filepath = '/upload/index/'.str_replace('\\','/',$filepath);
            //更新一下头像路径
            $imgs = Db::name('user')->where('user_id',session('user_id'))->field('user_img')->find();
            if($imgs){
            	Db::name('user')->where('user_id',session('user_id'))->setField('user_img',$filepath);
            }else{
            	Db::name('user')->where('user_id',session('user_id'))->update(['user_img'=>$filepath]);
            }
            $result = [
                'code' => '200',
                'filename' => $filepath
            ];
        }else{
            $result = [
                'code' => '404',
                'msg' => $info->getError(),
            ];
        }
        return json_encode($result);
    }
}