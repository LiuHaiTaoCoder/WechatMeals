<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/student/school.html";i:1566440196;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>订餐系统</title>
    <link rel="stylesheet" href="/static/index/style/school.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">      
    <link rel="stylesheet" href="/static/index/style/initial.css"> 
    <script>document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';</script>
</head>

<body>

        <div class="login_head">
           <a href="javascript:history.back(-1);"> <img src="/static/index/images/return.png"></a>
            <span>选择学校</span>
        </div>
        <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="school_main">
           <a href="/index/student/meals/id/<?php echo $vo['school_id']; ?>">
                <span><?php echo $vo['school_name']; ?></span>
                <img src="/static/index/images/right.png">
           </a>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
</body>
</html>