<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/user/print-s.html";i:1566440187;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title><?php echo $ins['class_name']; ?></title>
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
                <a><cite>学生管理</cite></a>
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
                    <a href="/admin/user/exportStudent"  class="layui-btn" id="export"><i class="layui-icon">&#xe601;</i>导出为Excel</a>
                </div>
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="name" placeholder="请输入姓名" autocomplete="off" class="layui-input"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body " id="print_table">
                    <table class="layui-table layui-form">
                        <tr>
                            <th style="width: 6%;">ID</th>
                            <th style="width: 6%;">学生名</th>
                            <th style="width: 6%;">性别</th>
                            <th style="width: 8%;">关联家长</th>
                            <th style="width: 10%;">家长联系方式</th>
                            <th style="width: 12%;">学校</th>
                            <th style="width: 15%;">备注</th>
                            <th style="width: 12%;">加入时间</th>
                        </tr>
                        <tbody>
                        <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['children_id']; ?></td>
                            <td><?php echo $vo['children_name']; ?></td>
                            <td>
                                <?php if($vo['children_sex'] == '1'): ?>
                                男
                                <?php else: ?>
                                女
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['user_name']; ?></td>
                            <td><?php echo $vo['user_mobile']; ?></td>
                            <td><?php echo $vo['school_name']; ?></td>
                            <td><?php echo $vo['remake']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<style media="print" type="text/css">
 /*   @page{
        size:auto;!*或landscape设置横纵向打印*!
        margin: 0mm;!*去掉页脚页码*!
    }*/
</style>
<script>
    $(function () {
        $('#print_table').print({
            globalStyles:true,//是否包含父文档的样式，默认为true
            mediaPrint:false,//是否包含media='print'的链接标签。会被globalStyles选项覆盖，默认为false
            stylesheet:null,//外部样式表的URL地址，默认为null
            noPrintSelector:".no-print",//不想打印的元素的jQuery选择器，默认为".no-print"
            iframe:false,//是否使用一个iframe来替代打印表单的弹出窗口，true为在本页面进行打印，false就是说新开一个页面打印，默认为true
            append:null,//将内容添加到打印内容的后面
            prepend:null,//将内容添加到打印内容的前面，可以用来作为要打印内容
            manuallyCopyFormValues: true,
            deferred: $.Deferred(function () {
                /*   confirm('是否打印成功!');*/
                location.href = '/admin/user/classRoom';
            })//回调函数
        });
        window.close();
    })
</script>

</html>