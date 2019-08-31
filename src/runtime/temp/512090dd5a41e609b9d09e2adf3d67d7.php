<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/payment/payBack.html";i:1567064488;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
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
<div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">缴费管理</a>
            <a>
              <cite>餐费补缴</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <a href="javascript:;" onclick="xadmin.open('设置交费时间','/admin/payment/setPayTime')" class="layui-btn setPayTime" id="export">设置交费时间</a>
                </div>
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="开始日期" name="start" id="start">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="截止日期" name="end" id="end">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="name"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>订单号</th>
                            <th>用户名/联系方式</th>
                            <th>补缴周期</th>
                            <th>补缴时长</th>
                            <th>补缴金额</th>
                            <th>所属套餐</th>
                            <th>补缴状态</th>
                            <th>补缴时间</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                        <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['payback_id']; ?></td>
                            <td><?php echo $vo['trade_no']; ?></td>
                            <td><?php echo $vo['user_name']; ?>：<?php echo $vo['user_mobile']; ?></td>
                            <td><?php echo $vo['payback_cycle']; ?></td>
                            <td><?php echo $vo['payback_day']; ?>天</td>
                            <td><?php echo $vo['payback_money']; ?></td>
                            <td><?php echo $vo['menu_name']; ?></td>
                            <td>
                                <?php switch($vo['payback_status']): case "0": ?>未支付<?php break; case "1": ?>已支付<?php break; case "2": ?>已完成<?php break; endswitch; ?>
                            </td>
                            <td><?php echo date("Y-m-d H:i:s",$vo['create_time']); ?></td>
                            <td class="td-manage">
                                <?php if($vo['payback_status'] != '2'): ?>
                                <a title="确认收货" onclick="confirm_receipt('<?php echo $vo['payback_id']; ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe6b2;</i></a>
                                <?php endif; ?>
                                <a title="删除" onclick="member_del(this,'<?php echo $vo['payback_id']; ?>')" href="javascript:;">
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
    layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var form = layui.form;

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
    function member_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $.get('/admin/payment/delPayBack/id/'+id,{},
                function (xhr) {
                if(xhr){
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                }else{
                    layer.msg('删除失败',{icon:2,time:2000});
                    return false;
                }
            })

        });
    }
    function confirm_receipt(id){
        var url = '/admin/payment/confirmReceipt/id/'+id;
        layer.confirm('确认要强制收货吗？', function(index) {
            $.get(url,{'status' : 2},
                function (result) {
                    if(result){
                        layer.msg('收货成功！', {
                            icon: 1,
                            time: 1000
                        });
                        xadmin.father_reload();
                        xadmin.close();
                    }else{
                        layer.msg('收货失败',{icon : 2,time:1000});
                        return ;
                    }
                });
        });
    }

</script>
</html>