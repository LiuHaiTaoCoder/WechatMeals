<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/user/core.html";i:1566875362;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>个人中心</title>
    <link rel="stylesheet" href="/static/index/style/initial.css">
    <link rel="stylesheet" href="/static/index/style/distance.css">
    <link rel="stylesheet" href="/static/index/style/core.css">
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    <script>document.documentElement.style.fontSize = document.documentElement.clientWidth / 3.75 + 'px';</script>
</head>

<body>
        <div class="login">
            <div class="login_head">
                <a href="/index/index/index"> <img src="/static/index/images/return.png"></a>
                <span>个人中心</span>
            </div>
        </div>

        <div class="conation">

            <div class="core_main Negative">
                <span>头像</span>
                <div>
                    <form id="newForm" enctype="multipart/form-data">
                        <!-- <img src="/static/index/images/renl.png" >-->

                        <?php if(!(empty($res['user_img']) || (($res['user_img'] instanceof \think\Collection || $res['user_img'] instanceof \think\Paginator ) && $res['user_img']->isEmpty()))): ?>
                            <img id="up_imga" onclick="getElementById('inputfilea').click()" style="cursor:pointer;max-width:100px;"
                                 title="点击添加图片" alt="点击添加图片" src="<?php echo $res['user_img']; ?>">
                        <?php else: ?>
                            <img id="up_imga" onclick="getElementById('inputfilea').click()" style="cursor:pointer;max-width:100px;"
                                 title="点击添加图片" alt="点击添加图片" src="/static/index/images/renl.png">
                        <?php endif; ?>

                        <input type="file" id="inputfilea"style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;" value="" lay-verify=""/>
                        <input type="hidden" id="imga" name="user_img" value="">
                        <img src="/static/index/images/right.png">
                    </form>
                </div>
            </div>

            <div class="core_main">
                <span>真实姓名</span>
                <span><?php echo $res['user_name']; ?></span>
            </div>

            <div class="core_main">
                <span>手机号码</span>
                <span><?php echo $res['user_mobile']; ?></span>
            </div>

            <div class="core_password">
                <a href="/index/user/modifyPar">
                    <span>修改密码</span>
                    <!--  </a>
                    <a href=""> -->
                    <img src="/static/index/images/right.png">
                </a>
            </div>

            <div class="core_edge">
                <span>关联学生信息</span>
                <img src="/static/index/images/szj.png" alt="">
            </div>
            <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <div class="core_main">
                <span><?php echo $vo['children_name']; ?></span>
                <a href="/index/user/students/id/<?php echo $vo['children_id']; ?>/ids/<?php echo $vo['school_id']; ?>">
                    <div>
                        <span>查看详情</span>
                        <img src="/static/index/images/right.png">
                    </div>
                </a>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!--底部升起-->
            <!--<div class="core_Rise">
               <a href="#">相册</a>
               <a href="#">拍照</a>
               <a href="#" class="cancel">取消</a>
            </div>-->
        </div>
        <div class="login_footer">
            <a href="#">退出登录</a>
        </div>
        <div class="package">
            <div>
                <span>确定退出登录吗?</span>
                <div>
                    <a href="#" class="olcancel">取消</a>
                    <a href="#" id="logout">确定</a>
                </div>
            </div>
        </div>
</body>
<script src="/static/index/js/Negative.js"></script>
<script>
    //退出登录
    $('#logout').click(function (){
    
        $.post('/index/login/quitLogin',function (xhr) {
        	console.log(xhr);
            //xhr = eval('(' + xhr + ')');
            if(xhr.status == 1){
                $('.package').hide();
                layer.msg(xhr.msg);
                setTimeout(function () {
                    location.href = '/index/login/passLoginPage';
                },1000)
            }else{
                layer.msg(xhr.msg);
            }
        },"json");
    })
    //响应文件添加成功事件
    $('#inputfilea').change(function(){
        var form_data = new FormData($("#newForm")[0]);
        var files = $(this)[0].files[0];
        form_data.append('file',files);

        $.ajax({
            url:'/api/Upload/formIndex',
            type:'post',
            data:form_data,
            processData:false,
            contentType:false,
            async:false,
            cache:false,
            dataType:'JSON',
            success:function(result){
                result = eval('(' + result + ')');
                if(result.code == 200){
                    layer.msg('上传成功!',{time : 2000});
                    $('#up_imga').attr("src",result.filename);
                    $('#imga').val(result.filename);
                    $('#up_imga').show();
                }
                else{
                    layer.msg(result.msg);
                }
            },
            error:function(e){
                 layer.msg('请选择图片格式文件或图片不能大于5MB');
                 return ;
            }
        });
    });
</script>
</html>