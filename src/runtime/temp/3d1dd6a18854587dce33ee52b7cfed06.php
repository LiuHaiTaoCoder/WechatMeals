<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/order/refund.html";i:1566973593;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
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
                <a href="">订单管理</a>
                <a>
                    <cite>退款处理</cite></a>
            </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="开始日" name="start" id="start"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="截止日" name="end" id="end"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="order" placeholder="请输入订单号" autocomplete="off" class="layui-input"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
            	<div class="layui-card-body ">
	                <blockquote class="layui-elem-quote">
	                        注意：退款到账会有一定延迟,退款成功后会原路退回。
	                        <br />
	                        1.零钱支付，退款完成在2分钟内立即到账
	                        <br />
	                        2.银行卡支付，退款完成后一般0-3个工作日之内到账，具体请查看银行收支明细
	                </blockquote>
                </div>
                <!--div class="layui-card-body">
                    <a href="" class="layui-btn " id="export">导出为Excel</a>
                </div>-->
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th style="width: 12%;">退款单号</th>
                            <th style="width: 7%;">家长</th>
                            <th style="width: 8%;">购买套餐</th>
                            <th style="width: 7%;">支付金额</th>
                            <th style="width: 7%;">退款金额</th>
                            <th style="width: 18%;">退款原因</th>
                            <th style="width: 7%;">退款状态</th>
                            <th style="width: 10%;">退款时间</th>
                            <th style="width: 8%;">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['refund_no']; ?></td>
                            <td><?php echo $vo['user_name']; ?></td>
                            <td><?php echo $vo['menu_name']; ?></td>
                            <td>￥<?php echo $vo['pay_money']; ?></td>
                            <td>￥<?php echo $vo['refund_money']; ?></td>
                            <td><?php echo $vo['refund_reason']; ?></td>
                            <td id='status'>
                                <?php switch($vo['refund_status']): case "1": ?>审核中<?php break; case "2": ?>已退款<?php break; default: endswitch; ?>
                            </td>
                            <td><?php echo date("Y-m-d H:i:s",$vo['create_time']); ?></td>
                            <td class="td-manage">
                            	<?php if(($vo['refund_status'] == 1)): ?>
                                <a title="确认退款" onclick="member_refund(this,'<?php echo $vo['refund_id']; ?>')" href="javascript:;">
                                	<i class="layui-icon">&#xe6b2;</i></a>
                                <?php endif; ?>	
                                    
                                <a title="删除" onclick="member_del(this,'<?php echo $vo['refund_id']; ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe640;</i></a>
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
<script>layui.use(['laydate', 'form'],
    function() {
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

/*用户-停用*/
function member_stop(obj, id) {
    layer.confirm('确认要停用吗？',
        function(index) {

            if ($(obj).attr('title') == '启用') {

                //发异步把用户状态进行更改
                $(obj).attr('title', '停用');
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!', {
                    icon: 5,
                    time: 1000
                });

            } else {
                $(obj).attr('title', '启用');
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!', {
                    icon: 5,
                    time: 1000
                });
            }

        });
}
/*用户-退款*/
function member_refund(obj, id) {
    var url = '/api/wxpay/refund/id/'+id;
    layer.confirm('确认要通过退款审核吗？', function(index) {
        $.get(url,{'refund_status':2},function (result) {
            if(result){
                //发异步
                $('#status').html("已退款");
                layer.msg('已通过审核,退款中!', {
                    icon: 1,
                    time: 1000
               });
           }else{
                //发异步删除数据
                layer.msg('审核失败!', {
                    icon: 2,
                    time: 1000
                });
            }
        })
    });
}


/*用户-删除*/
function member_del(obj, id) {
    var url = '/admin/order/refundDel/id/'+id;
    layer.confirm('确认要删除吗？', function(index) {
        $.get(url,{},function (result) {
            if(result){
                //发异步删除数据
                $(obj).parents("tr").remove();
                layer.msg('已删除!', {
                    icon: 1,
                    time: 1000
                });
            }else{
                //发异步删除数据
                layer.msg('删除失败!', {
                    icon: 1,
                    time: 1000
                });
            }
        })
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