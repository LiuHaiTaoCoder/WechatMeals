<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/meals/orderlist.html";i:1567060713;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>订餐列表</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="/static/index/style/orderlist.css">
        <script src="/static/index/js/layDate-v5.0.9/laydate/laydate.js"></script>
        <script>
            document.documentElement.style.fontSize = document.documentElement.clientWidth / 3.75 + 'px';
        </script>
        <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

        <script>
            //初始化一个日历
            var st = $('.start').html();
            var ed = $('.end').html();
            laydate.render({
                elem: '#test18'
                ,showBottom: false
                ,min : '<?php echo date("Y-m-d",$curr_date); ?>'
                ,max : '2020-01-17'
            });
        </script>
	</head>
<body>
    <div class="topbox">
        <a href="/index/index/index"><img src="/static/index/images/xioayu.png" alt=""></a>
        <div class="tille">我要订餐</div>
    </div>
    <div class="orderlist">
        <div class="message">
            <div>学校班级</div>
            <div><?php echo $res['school_name']; ?> <?php echo $res['class_name']; ?></div>
        </div>
        <div class="message">
            <div>学生姓名</div>
            <div><?php echo $res['children_name']; ?></div>
        </div>
        <div class="seectset">
            <h1>套餐类型</h1>
            <div class="setbox">
            <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): if( count($info)==0 ) : echo "" ;else: foreach($info as $k=>$vo): ?>
	             <div class="set" <?php if(($k == 0)): ?> style="color:rgb(0, 0, 0);" <?php endif; ?> onclick="priceSum('<?php echo $vo['menu_price']; ?>','<?php echo $vo['menu_id']; ?>')">
	                <img class="picbox" src="<?php echo $vo['menu_img']; ?>" alt="">
	                <p><span>￥</span><i class="menu_price"><?php echo $vo['menu_price']; ?></i></p>
	                <div class="menu_name"><?php echo $vo['menu_name']; ?></div>
	                <i class="borderbox" <?php if(($k == 0)): ?> style="display: inline-block;" <?php endif; ?>></i>
	            </div>
	            <?php endforeach; endif; else: echo "" ;endif; ?>
        	</div>
		 </div>
    <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): if( count($info)==0 ) : echo "" ;else: foreach($info as $k=>$vo): if(($k == 0)): ?>
    <div class="order">
        <div class="order_coin">
            <div class="order_left">订餐周期</div>
            <div class="order_right hidden"><span>按学期 </span> <img class="pic_bth" src="/static/index/images/dayu.png" alt=""></div>
        </div>
        <div class="order_coin">
            <div class="order_left">订餐开始日期</div>
            <div class="order_right start start_month"><?php echo date("Y-m-d",$curr_date); ?></div>
            <div class="order_right start start_week" style="display:none"><?php echo date("Y-m-d",$curr_date); ?></div>
        </div>
        <div class="order_coin">
            <div class="order_left">订餐结束日期</div>
            <div class="order_right end end_month">2020-01-17</div>
            <div class="order_right end end_week" style="display:none">2020-01-17</div>
        </div>
        <div class="order_coin">
            <div class="order_left">实际配送天数</div>
            <div class="order_right">
                <span class="real_day">95</span>天
                <i class="linebox"></i>
                <!-- <a href="calendar.html"> -->
                <a href="#" class="days">
                    <span id="test18"><?php echo date("Y-m-d",$curr_date); ?></span>
                    <span style="font-size: .13rem;">查看配送日期</span>
                </a>
            </div>
        </div>
        <div class="order_coin">
            <div class="order_left">应支付金额</div>
            <div class="order_right">测试金额<span class="buck">￥</span><i class="price">0.01</i></div>
            <input type="hidden" class="menu_id" value="<?php echo $vo['menu_id']; ?>">
            <input type="hidden" class="school_id" value="<?php echo $res['school_id']; ?>">
            <input type="hidden" class="class_id" value="<?php echo $res['class_id']; ?>">
            <input type="hidden" class="children_id" value="<?php echo $res['children_id']; ?>">
        </div>
        <div class="order_on">
            <div class="order_bth sub">确认订单</div>
        </div>

        <div class="order_wrap">
            <div class="order_bottom">
                <a class="semester">按学期</a>
                <a class="month">按月</a>
                <a class="week">按周</a>
            </div>
        </div>
    </div>
    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</body>
<script src="/static/index/js/orderlistbox.js"></script>
</html>