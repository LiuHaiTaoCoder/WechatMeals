<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/user/class.html";i:1566786982;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
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
    <script src="/static/admin/js/jQuery.print.js" type="text/javascript"></script>
</head>

<body>
<div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">用户管理</a>
                <a>
                    <cite>班级管理</cite></a>
            </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('添加','/admin/user/classAdd')"><i class="layui-icon"></i>添加</button>
                    <a href="/admin/common/exportClass"  class="layui-btn" id="export"><i class="layui-icon">&#xe601;</i>导出为Excel</a>
               </div>
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="name" placeholder="请输入班级名" autocomplete="off" class="layui-input"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body " id="print_table">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th style="width: 6%;">ID</th>
                            <th>班级名</th>
                            <th>订餐人数</th>
                            <th>所属学校</th>
                            <th>学校地址</th>
                            <th>学校分类</th>
                            <th>加入时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['class_id']; ?></td>
                            <td><?php echo $vo['class_name']; ?></td>
                            <td><?php echo $vo['number']; ?>人</td>
                            <td><?php echo $vo['school_name']; ?></td>
                            <td><?php echo $vo['address']; ?></td>
                            <td>
                                <?php switch($vo['school_cate']): case "1": ?>幼儿园<?php break; case "2": ?>小学<?php break; case "3": ?>初中<?php break; case "4": ?>高中<?php break; case "5": ?>大学<?php break; default: endswitch; ?>
                            </td>
                            <td><?php echo $vo['ctime']; ?></td>
                             <td class="td-manage"></a>
                                <a onclick="xadmin.open('修改','/admin/user/classEdit/id/<?php echo $vo['class_id']; ?>/ids/<?php echo $vo['school_id']; ?>')" title="修改" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                 <a href="javascript:;" onclick="printTable(this,'<?php echo $vo['class_id']; ?>')" title="打印报表" class="">
                                     <i class="layui-icon">&#xe655;</i>
                                 </a>
                                 <a title="删除" onclick="member_del(this,'<?php echo $vo['class_id']; ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <?php echo $page; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function printTable(obj,id){
       location.href="/admin/user/studentPrint/id/"+id;
    }
    layui.use(['laydate', 'form'], function() {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });
    });
/*删除*/
function member_del(obj, id) {
    var url = '/admin/user/classDel/id/'+id;
    layer.confirm('确认要删除吗？', function(index) {
        $.get(url, {}, function (xhr){
                if(xhr){
                    //发异步删除数据
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                }else{
                    layer.msg('删除失败!',{icon:2,time:1000});
                }
            }
        )
    });
}



function delAll(argument) {

    var data = tableCheck.getData();

    layer.confirm('确认要删除吗？' + data,
        function(index) {
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {
                icon: 1
            });
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
}</script>

</html>