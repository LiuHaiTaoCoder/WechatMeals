<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/payment/setPayTime.html";i:1566974080;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
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
    <style type="text/css">
        input[type=date]{
            width: 140px;
            font-size: 14px;
            margin: 2px 0px 0px 5px;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form action="" method="post" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>开始时间</label>
                <div class="layui-input-block">
                    <input type="date" value="" name="start_time" lay-verify="nikename" min="" id="start_time"  required="required" lay-skin="primary" class="layui-form-text" >
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>结束时间</label>
                    <div class="layui-input-block">
                        <input type="date" value="" name="end_time" id="end_time" min="" lay-verify="nikename" required="required" lay-skin="primary" class="layui-form-text">
                    </div>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="desc" class="layui-form-label">
                    备注(可不填)
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="desc" name="desc" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="add">设置</button>
            </div>
        </form>
    </div>
</div>
<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //自定义验证规则
        form.verify({
            nikename: function(value) {
                if (value.length < 1) {
                    return '开始日期和结束不能为空！';
                }
            },
        });

        //监听提交
        form.on('submit(add)', function(data){
            var start_date = $('#start_time').val();
            var end_date = $('#end_time').val();

           /* console.log(data);*/
            //发异步，把数据提交给php
            $.ajax({
                url : '/admin/payment/paytime'
                ,data : {
                   'start_date' : start_date,
                   'end_date' : end_date,
                   'paytime_remark' : data.field.desc,
                },dataType : 'json'
                ,type : 'post'
                ,async : false
                ,success : function(xhr){
                    xhr = eval('(' + xhr + ')');
                    if(xhr.status == 1){
                        layer.alert("设置成功", {icon: 6},function () {
                            // 关闭当前窗口
                            xadmin.close();
                            //刷新父窗口
                            xadmin.father_reload();
                        });
                    }else{
                        layer.alert('设置失败',{icon: 2});
                        return false;
                    }
                }
            });
            return false;
        });


/*        form.on('checkbox(father)', function(data){

            if(data.elem.checked){
                $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                form.render();
            }else{
                $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                form.render();
            }
        });*/


    });
</script>
</body>

</html>