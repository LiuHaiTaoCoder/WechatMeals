<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/login/index.html";i:1566440178;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>后台登录-总后台管理</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/static/admin/css/login.css">
    <link rel="stylesheet" href="/static/admin/css/font.css">
<link rel="stylesheet" href="/static/admin/css/xadmin.css">
<script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
<script type="text/javascript" src="/static/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/admin/js/jquery-3.3.1.js"></script>
<!-- 兼容IE8 IE9 其实也没啥用谁还用IE哈哈哈，从而兼容栅格 -->
<!-- [if lt IE 9]>
<script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
<script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">订餐系统后台-管理登录</div>
        <div id="darkbannerwrap"></div>

        <div class="layui-form">
            <input name="admin_name" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="admin_pwd" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <div class="layui-form-item">
                <div class="layui-input-inline">
                    <input name="code" lay-verify="required" placeholder="验证码"  type="text" class="layui-input">
                </div>
                <img src="<?php echo captcha_src(); ?>" id="val_code" name="val_code" onclick="checkCode()" alt="captcha" style="width: 40%;height: 50px;"/>
            </div>
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </div>
    </div>

    <script>
        function checkCode() {
            $('#val_code').attr('src','<?php echo captcha_src(); ?>?rand='+Math.random());
        }
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
              //监听提交
              form.on('submit(login)', function(){
                  var admin_name = $('input[name=admin_name]').val();
                  var admin_pwd = $('input[name=admin_pwd]').val();
                  var code = $('input[name=code]').val();
                    $.ajax({
                        type  : 'post'
                        ,url : '/admin/login/login'
                        ,data : {
                            'admin_name' : admin_name
                            ,'admin_pwd' : admin_pwd
                            ,'code' : code
                        }
                        ,dataType : 'json'
                        ,ccache : false
                        ,success : function(xhr){
                            xhr = eval('('+ xhr +')');
                            if (xhr.status == 1){
                                layer.msg(xhr.msg);
                                setTimeout(function(){
                                        location.href='/admin/index/index'
                                    },1000
                                )
                            }else{
                                checkCode();
                                $('input[name=admin_name]').val('');
                                $('input[name=admin_pwd]').val('');
                                $('input[name=code]').val('');
                                $('input[name=admin_name]').focus();
                                layer.msg(xhr.msg,{icon:2});
                                return false;
                            }
                        },error : function (){
                            layer.msg('ajax出错请检查参数是否正确');
                            return false;
                        }
                    });
              });
            });
        })
    </script>
</body>
</html>