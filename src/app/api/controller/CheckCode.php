<?php
namespace app\api\controller;

use think\Captcha;

class Checkcode
{
    //创建一个方法
    public function code(){
        //实例化类
        $captcha = new Captcha();
        //生成验证码并返回
        return $captcha->entry();
    }
}
