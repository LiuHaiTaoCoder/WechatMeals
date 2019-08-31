<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/leave/myLeave.html";i:1566643835;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>我的请假</title>
    <link rel="stylesheet" href="/static/index/style/initial.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">
    <link rel="stylesheet" href="/static/index/style/modify.css">
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    <script>document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';</script>
</head>

<body>
        <div class="login">
                <div class="login_head">
                        <a href="javascript:history.back(-1);"> <img src="/static/index/images/return.png"></a>
                         <span>我的请假</span>
                         <a class="leave">去请假</a>
                </div>
            <input type="hidden" class="id" value="<?php echo \think\Request::instance()->param('id'); ?>">
        </div>
        
        <div class="conationY">
            <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <div>
                    <span>请假时间: <span style="font-size: .12rem;"><?php echo $vo['start_date']; ?>日~<?php echo $vo['end_date']; ?>日</span></span>
                    <span>请<?php echo $vo['leave_day']; ?>天</span>
                    <span>
                        <?php switch($vo['leave_status']): case "1": ?>正在审核<?php break; case "2": ?>退款成功<?php break; endswitch; ?>
                    </span>
                </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
</body>
<script>
    $('.leave').on('click',function () {
        var id = $('.id').val();
        $.ajax({
            url : '/index/leave/isTime',
            data : {},
            type : 'post',
            ccache : false,
            success : function (xhr) {
                if(xhr.status == 1){
                    location.href='/index/leave/leave/id/'+id;
                }else{
                    layer.msg('当前不是请假时间');
                    return ;
                }
            }
        })
    })
</script>
</html>