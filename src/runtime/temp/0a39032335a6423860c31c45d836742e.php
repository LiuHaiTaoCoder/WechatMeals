<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/order/myRefund.html";i:1566789720;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>我的退款</title>
    <link rel="stylesheet" href="/static/index/style/initial.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">
    <link rel="stylesheet" href="/static/index/style/modify.css">
    <link rel="stylesheet" href="/static/index/style/myRefund.css">
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    <script>document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';</script>
</head>

<body>

        <div class="login">
                <div class="login_head">
                        <a href="javascript:history.back(-1);"> <img src="/static/index/images/return.png"></a>
                        <span>我的退款</span>
                        <a class="myrefund">申请退款</a>
                </div>
            <input type="hidden" name="id" class="id" value="<?php echo \think\Request::instance()->param('id'); ?>">
        </div>

       <div class="refund">
        <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<div class="refund_item">
				<span>余额退款</span>
				<?php if(($vo['refund_status']==2)): ?>
				<span>： +<?php echo $vo['refund_money']; ?></span>
				<?php endif; ?>
				<span>
                        <?php switch($vo['refund_status']): case "1": ?>退款中<?php break; case "2": ?>退款成功<?php break; default: ?>审核中
                        <?php endswitch; ?>
                 </span>
				<p><?php echo date("Y-m-d H:i:s",$vo['create_time']); ?></p>
			</div>
        <?php endforeach; endif; else: echo "" ;endif; if(is_array($leres) || $leres instanceof \think\Collection || $leres instanceof \think\Paginator): $i = 0; $__LIST__ = $leres;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$so): $mod = ($i % 2 );++$i;?>
			<div class="refund_item">
				<span>请假停餐退款</span>
					<?php if(($so['leave_status']==2)): ?>
				<span>:	+<?php echo $so['refund_money']; ?></span>
				<?php endif; ?>
				<span>
                        <?php switch($so['leave_status']): case "1": ?>审核中<?php break; case "2": ?>退款成功<?php break; default: ?>审核中
                        <?php endswitch; ?>
                 </span>
                <p><?php echo $so['create_time']; ?></p>
			</div>			
        <?php endforeach; endif; else: echo "" ;endif; ?>
       
       </div>

</body>
<script>
    $('.myrefund').on('click',function () {
        var id = $('.id').val();
        $.ajax({
            url : '/index/order/isOrder/id/'+id,
            data : {},
            type : 'post',
            ccache : false,
            success : function (xhr) {
                if(xhr.status == 1){
                    location.href='/index/order/refund/id/'+id;
                }else{
                    layer.msg('暂未获取到订单');
                    return ;
                }
            }
        })
    })
</script>
</html>