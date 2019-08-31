<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/user/modify.html";i:1566875467;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>修改密码</title>
    <link rel="stylesheet" href="/static/index/style/initial.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">
    <link rel="stylesheet" href="/static/index/style/modify.css">
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    <script>document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';</script>
</head>

<body>

        <div class="login">
                <div class="login_head">
                        <a href="/index/user/personal"> <img src="/static/index/images/return.png"></a>
                        <span>修改登录密码</span>
                        <a href="#" class="storage">保存</a>
                </div>
        </div>
        <form>
       <div class="conation">

            <div class="modify_main">
                    <input type="password" name="old_pwd" placeholder="请输入已有登录密码">
                </div>
                <div class="modify_main">
                    <input type="password" name="user_pwd" placeholder="请输入新的登录密码">
                </div>
       </div>
        </form>

</body>
<script>
    $('.storage').click(function () {
        var oldpwd = $('input[name=old_pwd]').val();
        var newpwd = $('input[name=user_pwd]').val();

        if(!oldpwd){
            layer.msg('旧密码不能为空');
            $('input[name=old_pwd]').focus();
            return ;
        }else if(!newpwd){
            layer.msg('新密码不能为空');
            $('input[name=user_pwd]').focus();
            return ;
        }

        $.ajax({
            url : '/index/user/modifyParUpdate'
            ,data : {
                'old_pwd' : oldpwd,
                'user_pwd' : newpwd
            },dataType : 'json'
            ,type : 'post'
            ,ccache : false
            ,success : function (xhr) {
                xhr = eval('(' + xhr + ')');
                if(xhr.status == 1){
                    layer.msg(xhr.msg);
                    setTimeout(function () {
                        location.href = '/index/index/index';
                    },1500)
                }else if(xhr.status == 2){
                    layer.msg(xhr.msg);
                    $('form')[0].reset();
                    $('input[name=old_pwd]').focus();
                    return ;
                }else {
                    layer.msg(xhr.msg);
                    $('input[name=user_pwd]').val('');
                    $('input[name=user_pwd]').focus();
                    return ;
                }
            },error : function (){
                layer.alert('ajax出错!');
                return ;
            }

        });
    })

</script>
</html>