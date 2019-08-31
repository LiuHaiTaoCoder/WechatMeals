<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/login/register.html";i:1566466967;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户注册</title>
    <link rel="stylesheet" href="/static/index/style/Login.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">
    <link rel="stylesheet" href="/static/index/style/initial.css">
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    <script>document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';</script>
    <style type='text/css'>
    	@media (max-height: 500px){
			.login_footer{
				display: none;
			}
		};
    </style>
</head> 
 
<body>
        <div class="login">
                <div class="login_head">
                        <a href="javascript:history.back(-1);"> <img src="/static/index/images/return.png"></a>
                        <span>注册</span>
                </div>
        </div>

        <form>
        <div class="login_main">

            <div class="aligns">
                <label>真实姓名</label>
                <input type="text" name="user_name" placeholder="请输入您的真实姓名">
            </div>

           <div class="aligns">                 
                <label>手机号</label>
                <input class="left" name="user_mobile" type="text" placeholder="请输入您的手机号">
        </div>

            <div class="item">
                <label>验证码</label>
                <input class="leftT" name="code"  type="text" placeholder="请输入短信验证码">
                <button class="time">获取验证码</button>
            </div>

            <div class="aligns">
                <label>登录密码</label>
                <input type="password" name="user_pwd" placeholder="请设置登录密码">
            </div>

            <div class="aligns">
                <label>确认密码</label>
                <input type="password" name="user_repwd" placeholder="请确认登录密码">
            </div>
        
        </div>

        <div class="login_footer">
            <span><a href="/index/login/passLoginPage/">已有账号,去登录</a></span>
            <a class="sub">提交注册</a>
        </div>

</form>
</body>
<script src="/static/index/js/Countdown.js"></script>
</html>