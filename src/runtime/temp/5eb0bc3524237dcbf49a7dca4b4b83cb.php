<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:81:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/user/parents-edit.html";i:1566461593;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>欢迎页面</title>
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
<div class="x-body layui-anim layui-anim-up" style="margin-top: 20px; ">
    <form class="layui-form" id="newForm" enctype="multipart/form-data">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>用户名
            </label>
            <div class="layui-input-inline" style=>
                <input type="text" id="username" name="user_name" required=""
                       autocomplete="off" class="layui-input" value="<?php echo $res['user_name']; ?>">
                <input type="hidden" value="<?php echo $res['user_id']; ?>" name="user_id"/>
            </div>

        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>性别</label>
            <div class="layui-input-block">
                <input type="radio" name="user_sex" value="0" lay-skin="primary" title="未知" <?php if($res['user_sex'] == '0'): ?>checked <?php endif; ?>>
                <input type="radio" name="user_sex" value="1" lay-skin="primary" title="男" <?php if($res['user_sex'] == '1'): ?>checked <?php endif; ?>>
                <input type="radio" name="user_sex" value="2" lay-skin="primary" title="女" <?php if($res['user_sex'] == '2'): ?>checked <?php endif; ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="imga" class="layui-form-label">
                头像
            </label>
            <div class="layui-input-inline">
                <!--没有图片显示默认图片-->
                <input type="file" id="inputfilea" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;" value="" lay-verify=""/>
                <input type="hidden" id="imga" name="user_img" value="">
                <?php if(empty($res['user_img']) || (($res['user_img'] instanceof \think\Collection || $res['user_img'] instanceof \think\Paginator ) && $res['user_img']->isEmpty())): ?>
                <img id="up_imga" onclick="getElementById('inputfilea').click()" style="cursor:pointer;max-width:200px;" title="点击添加图片" alt="点击添加图片" src="/static/admin/images/user-default.jpg">
                <?php else: ?>
                <img id="up_imga" onclick="getElementById('inputfilea').click()" style="cursor:pointer;max-width:200px;" title="点击添加图片" alt="点击添加图片" src="<?php echo $res['user_img']; ?>">
                <?php endif; ?>
            </div>

        </div>
        <div class="layui-form-item">
            <label for="phonenumber" class="layui-form-label">
                <span class="x-red">*</span>手机号
            </label>
            <div class="layui-input-inline">
                <input type="text" name="user_mobile" id="phonenumber" class="layui-input" required="" value="<?php echo $res['user_mobile']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label  class="layui-form-label">
            </label>
            <button  class="layui-btn" id="new_sub" lay-filter="edit" lay-submit="">
                修改
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form', 'layer'], function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer;

            //监听提交
            form.on('submit(edit)', function(data) {
                var img = $('#up_imga').attr('src');
                //console.log(data);
                //发异步，把数据提交给php
                $.ajax({
                    url : '/admin/user/parentsUpdate'
                    ,data : {
                        'user_id' : data.field.user_id,
                        'user_name' : data.field.user_name,
                        'user_sex' : data.field.user_sex,
                        'user_img' : img,
                        'user_mobile' : data.field.user_mobile,}
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
                            layer.alert("修改失败，已经存在该用户",{icon : 3});
                            return false;
                        }else{
                            layer.alert("修改失败!");
                            return false;
                        }
                    }
                });
                return false;
            });
    });
    //响应文件添加成功事件
    $('#inputfilea').change(function(){
        var form_data = new FormData($("#newForm")[0]);
        var files = $(this)[0].files[0];
        form_data.append('file',files);

        $.ajax({
            url:'/api/Upload/form',
            type:'post',
            data:form_data,
            processData:false,
            contentType:false,
            async:false,
            cache:false,
            dataType:'JSON',
            success:function(result){
                result = eval('(' + result + ')');
                if(result.code == 200){
                    layer.msg('成功!',{time : 2000});
                    $('#up_imga').attr("src",result.filename);
                    $('#imga').val(result.filename);
                    $('#up_imga').show();
                }
                else{
                    layer.msg(result.msg);
                }
            },
            error:function(){
                alert('错误!');
            }
        });
    });
</script>

</body>

</html>