<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/student/meals.html";i:1566462409;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加学生</title>
    <link rel="stylesheet" href="/static/index/style/school.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">
    <link rel="stylesheet" href="/static/index/style/initial.css">
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    <script>document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';</script>
</head>

<body> 

        <div class="login_head">
           <a href="javascript:history.back(-1);"> <img src="/static/index/images/return.png"></a>
            <span>添加用餐学生</span>
        </div>
        <form>
        <div class="meals_main">
            <span>学校</span>
            <span style="color: black;"><?php echo $sc['school_name']; ?></span>
            <input type="hidden" name="school_id" value="<?php echo $sc['school_id']; ?>">
        </div>
        <div class="meals_main top">
                <span>年级</span>
               <div>
                   <div>
                       <select name="grade_id" id="grade_id">
                           <option value="">选择年级</option>
                           <?php if(is_array($gr) || $gr instanceof \think\Collection || $gr instanceof \think\Paginator): $i = 0; $__LIST__ = $gr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($i % 2 );++$i;?>
                           <option value="<?php echo $go['grade_id']; ?>"><?php echo $go['grade_name']; ?></option>
                           <?php endforeach; endif; else: echo "" ;endif; ?>
                       </select>
                       <img src="/static/index/images/right.png">
                   </div>
               </div>
        </div>

        <div class="meals_main">
                <span>班级</span>
                <div>
                   <select name="class_id" id="class_id">
                       <option value="">请选择班级</option>
                   </select>
                    <img src="/static/index/images/right.png">
                </div>
        </div>

        <div class="meals_main">
                <span>学生姓名</span>
                <input type="text" name="children_name" value="" placeholder="请输入学生姓名">
        </div>

        <div class="meals_main">
                <span>性别</span>
                <div>
                    <input type="radio" name="children_sex" value="0" title="" checked>男
                    <input type="radio" name="children_sex" value="1" title="">女
                </div>
        </div>

        <div class="itemX"> 
            <textarea placeholder="用餐备注" name="remake" id="" cols="45" rows="3"></textarea>
        </div>

        <div class="login_footer">
                <a class='Addto'>确认添加</a><!--/index/student/mealsInsert-->
        </div>
        </form>
</body>
<script src="/static/index/js/Addto.js"></script>
</html>