<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/order/Payback.html";i:1566440195;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>我的补缴</title>
    <link rel="stylesheet" href="/static/index/style/initial.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">
    <link rel="stylesheet" href="/static/index/style/modify.css">
    <script>document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';</script>
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

</head> 

<body>

        <div class="login">
                <div class="login_head">
                        <a href="javascript:history.back(-1);"> <img src="/static/index/images/return.png"></a>
                         <span>我的补缴</span>
                </div>
        </div>
        
        <div class="conationX">
        <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <div class="items">
               <div>
                   <span>订单号:<?php echo $vo['trade_no']; ?></span>
                   <?php switch($vo['payback_status']): case "0": ?><span style="color: #E93030;">未付款</span><?php break; case "1": ?><span style="color: #5BC770;">已付款</span><?php break; case "2": ?><span style="color: #5BC770;">已完成</span><?php break; default: ?><span style="color: #999999;">已取消</span>
                   <?php endswitch; ?>
               </div>
               <div>
                   <span><?php echo $vo['menu_name']; ?></span>
                   <span>总价￥<?php echo $vo['payback_money']; ?></span>
               </div>
             </div>
        <div class="hr"></div>
         <?php endforeach; endif; else: echo "" ;endif; ?>

        </div>

</body>

</html>