<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/user/parents-password.html";i:1566440187;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
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
    
    <body>
        <div class="layui-fluid">
            <div class="layui-row">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <label for="L_username" class="layui-form-label">用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" id="L_username" name="username" disabled="" value="<?php echo $res['user_name']; ?>" class="layui-input"></div>
                            <input type="hidden" name="user_id" value="<?php echo $res['user_id']; ?>">
                    </div>
                    <div class="layui-form-item">
                        <label for="L_oldpass" class="layui-form-label">
                            <span class="x-red">*</span>旧密码</label>
                        <div class="layui-input-inline">
                            <input type="password" id="L_oldpass" name="old_pwd" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_pass" class="layui-form-label">
                            <span class="x-red">*</span>新密码</label>
                        <div class="layui-input-inline">
                            <input type="password" id="L_pass" name="new_pass" required="" lay-verify="repass" autocomplete="off" class="layui-input"></div>
                        <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label">
                            <span class="x-red">*</span>确认密码</label>
                        <div class="layui-input-inline">
                            <input type="password" id="L_repass" name="new_pwd" required="" lay-verify="repass" autocomplete="off" class="layui-input"></div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label"></label>
                        <button class="layui-btn" lay-filter="edit" lay-submit="">修改</button></div>
                </form>
            </div>
        </div>
        <script>
            layui.use(['form', 'layer'], function() {
                $ = layui.jquery;
                var form = layui.form,
                layer = layui.layer;

                //自定义验证规则
                form.verify({
                    pass: [/(.+){6,16}$/, '密码必须6到16位'],
                    repass: [/(.+){6,16}$/, '密码必须6到16位'],
                    repass: function(value) {
                        if ($('#L_pass').val() != $('#L_repass').val()) {
                            return '两次密码不一致';
                        }
                    }
                });

                //监听提交
                form.on('submit(edit)', function(data) {
                    console.log(data);
                    $.ajax({
                        url : '/admin/user/parentsUpPassword'
                        ,data : {
                            'new_pwd' : data.field.new_pwd,
                            'old_pwd' : data.field.old_pwd,
                            'user_id' : data.field.user_id,}
                        ,dataType: 'json'
                        ,type: 'post'
                        ,success : function (xhr) {
                            if(xhr.status == 1){
                                layer.alert("修改成功", {icon: 6}, function() {
                                    //关闭当前frame
                                    xadmin.close();
                                    // 可以对父窗口进行刷新
                                    xadmin.father_reload();
                                });
                            }else if(xhr.status == 2){
                                layer.alert("旧密码错误!",{icon : 3});
                                return false;
                            }else{
                                layer.alert("修改失败，新密码不能与旧密码错误!",{icon : 2});
                                return false;
                            }
                        }
                    });
                    return false;
                });

            });
        </script>
    </body>

</html>