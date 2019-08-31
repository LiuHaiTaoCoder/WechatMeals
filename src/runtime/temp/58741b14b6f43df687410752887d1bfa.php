<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/order/refund.html";i:1566714538;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/static/index/style/orderlist.css">
    <link rel="stylesheet" href="/static/index/style/Login.css">
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    <script>
        document.documentElement.style.fontSize = document.documentElement.clientWidth / 3.75 + 'px';
    </script>
    <script src="/static/index/js/layDate-v5.0.9/laydate/laydate.js"></script>
    <title>申请退款</title>
    <script>
        function DateDiff(sDate1, sDate2) { //sDate1和sDate2
            var aDate, oDate1, oDate2, iDays
            aDate = sDate1.split("-")
            oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]) //转换为9-25-2017格式
            aDate = sDate2.split("-")
            oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0])
            iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24) //把相差的毫秒数转
            // 换为天数
            return iDays
        }
        laydate.render({
            elem: '#start_date',
            min: '<?php echo $res['start_dates']; ?>',
            max: '<?php echo $res['end_date']; ?>',
            done : function (value){
                $('.start').val(value);
                var end = $('.end').val();
                /*console.log(end);*/
                if(end > value){
                    $('#day').html(DateDiff(value,end));
                }else{
                    $('#day').html(0);
                }
            }
        });
        laydate.render({
            elem: '#end_date',
            min: '<?php echo $res['end_dates']; ?>',
            max: '<?php echo $res['end_date']; ?>',
            done : function (value) {
                $('.end').val(value);
                var hidd = $('input[type=hidden]').val();
                if(hidd >= value){
                    layer.msg('结束日期不能小于开始日期,请重试',{time : 2000});
                    return ;
                }else{
                    $('#day').html(DateDiff(hidd,value));
                }
            }
        });
    </script>
    <style>
        .order_coin {
            position: relative;
        }
        .refund{
            font-size: .14rem;
        }
        .days {

            flex: 1;
            background: green;
        }

        .days span {
            position: absolute;
            right: 0;
            top: 0;
            z-index: 100;
            margin-top: 0.12rem;
            padding-right: 4%;
        }

        .days span:nth-of-type(1) {
            z-index: 101;
            opacity: 0;
            margin-top: 0.12rem;
        }

        .laydate-day-mark {
            background: rgb(34, 218, 34);
        }

        .laydate-day-mark::after {
            background: rgb(34, 218, 34);
        }

        /*申请退款*/
        .orderlist .order .order_coin .order_right span.fontColor {
            font-size: 0.13rem;
            color: #5BC770;
        }

        .orderlist .order .order_coin .order_right .linebox {
            margin: 0 .95rem 0 0.12rem;
        }

        .orderlist .order {
            border-top: 0rem solid #F5F5F5;
        }

        .days span {
            margin-top: 0.14rem;
        }

        .orderlist .order .order_on {
            margin: 3.65rem 0 0.15rem 0;
        }

        .login_footer {
            margin: 0 auto 0.1rem;
            width: 90%;
        }

        .login_footer span {
            color: rgba(0, 0, 0, .6);
            font-size: .13rem;
        }

        .login_footer span a {
            color: #5BC770;
            margin: 0 0.05rem;
            font-size: .13rem;
        }
        @media (max-height: 500px){
        	.login_footer{
        		display: none;
        	}
        }
    </style>
</head>

<body>
    <div class="topbox">
        <a href="javascript:;history.back(-1);"><img src="/static/index/images/xioayu.png" alt=""></a>
        <div class="tille">申请退款</div>
    </div>
    <div class="orderlist">
        <div class="order">
            <div class="order_coin">
                <div class="order_left">退订开始日期</div>
                <div class="order_right" id="start_date"><?php echo $res['start_dates']; ?></div>
                <input type="hidden" value="<?php echo $res['start_dates']; ?>" class="start">
                <input type="hidden" value="<?php echo $res['end_dates']; ?>" class="end">
            </div>
            <div class="order_coin">
                <div class="order_left">退订结束日期</div>
                <div class="order_right" id="end_date"><?php echo $res['end_dates']; ?></div>
            </div>
            <div class="order_coin">
                <div class="order_left">实际退订天数</div>
                <div class="order_right">
                    <span id="day">1</span>天
                    <!--<i class="linebox"></i>
                      <a href="#" class="days">
                        <span class="ondate" id="test18">
                          2019-08-14-->
                    <!-- 此日期隐藏起来了 样式在此页面顶部 opacity:0 -->
                    <!--</span>
                    <span class="fontColor">查看配送日期</span>
                </a>-->
                </div>
            </div>
            <div class="itemX" style=" margin: 0 auto 0.1rem;width: 90%;padding-top: 10px">
                <textarea class="title" cols="35" rows="3" placeholder="退款原因"></textarea>
            </div>

        </div>
    </div>
    <div class="login_footer">
        <span>本页面仅提供余额退款，请假退款请前往<a href="/index/leave/myLeave/id/<?php echo \think\Request::instance()->param('id'); ?>">我的请假</a></span>
        <a class="sub">申请退款</a>
    </div>
</body>
<script>
    $('.sub').click(function (){
        var start_date = $('#start_date').html();
        var end_date = $('#end_date').html();
        var day = $('#day').html();
        var reason = $('.title').val();

        if(day == 0){
            layer.msg('退订天数不能为空');
            return ;
        }else if(!reason){
            layer.msg('请填写原因');
            $('.title').focus();
            return ;
        }

        $.ajax({
            url : '/index/order/refundInsert'
            ,dataType : 'JSON'
            ,data : {
                'start_date' : start_date
                ,'end_date' : end_date
                ,'refund_day' : day
                ,'refund_reason' : reason
                ,'children_id' : '<?php echo \think\Request::instance()->param('id'); ?>'
            },type : 'post'
            ,ccache : false
            ,success : function (xhr) {
                xhr = eval('(' + xhr + ')');
                if(xhr.status == 1){
                    layer.msg(xhr.msg);
                    setTimeout(function () {
                        location.href= '/index/index/index'
                    },1000)
                }else if(xhr.status == 2){
                    layer.msg(xhr.msg);
                    return ;
                }else if(xhr.status == 3){
                    layer.msg(xhr.msg);
                    return ;
                }else{
                    layer.msg(xhr.msg);
                    return ;
                }
            },error : function(e){
                layer.alert('ajax出错');
            }
        })
    });
</script>
</html>