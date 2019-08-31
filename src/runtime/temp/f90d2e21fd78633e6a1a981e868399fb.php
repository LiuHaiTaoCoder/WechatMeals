<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/message/feedback.html";i:1566900461;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<html  lang="zh-CN">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,minimal-ui:ios">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>意见反馈</title>
        <link rel="stylesheet" href="/static/index/style/feedback.css" type="text/css">
        <link href="/static/index/style/common.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="/static/index/style/distance.css">
        <script src="/static/index/js/jquery-1.8.3.min.js"></script>
    	<link href="/static/index/style/dmaku.css" type="text/css" rel="stylesheet">
        <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

    </head>
    <script>
        document.documentElement.style.fontSize=document.documentElement.clientWidth / 3.75 + 'px';
    </script>
    <body>
        <div class="login">
                <div class="login_head"> 
                      <a href="/index/index/index"> <img src="/static/index/images/return.png"></a>
                      <span>意见反馈</span>
                </div>
        </div>
            <div id="suggest">
                <textarea id="textbox" placeholder="请在此输入您的问题或建议"></textarea>
            </div>

            <div class="z_photo upimg-div clear" id="img">
                <div class="z_file fl">
                    <form id="newForm" enctype="multipart/form-data">
                        <img id="up_imga" onclick="getElementById('inputfilea').click()" style="cursor:pointer;width:100px;height: 100px;"
                             title="点击添加图片"  class="add-img" alt="点击添加图片" src="/static/index/images/add.png">
                        <input type="file" id="inputfilea" class="file" style="" value="" lay-verify=""/>
                        <input type="hidden" id="imga" name="message_img" value="">
                    </form>
                </div>
            </div>
         <!--   <aside class="mask works-mask">
                <div class="mask-content">
                    <p class="del-p ">您确定要删除作品图片吗？</p>
                    <p class="check-p"><span class="del-com wsdel-ok">确定</span><span class="wsdel-no">取消</span></p>
                </div>
            </aside>-->
             <div class="login_footer">
                    <a class="sub">确认提交</a>
            </div>
    </body>
    <script>
        
        $('.sub').click(function () {
            var img =  $('#imga').val();
            var content =  $('#textbox').val();
            if(!content){
                layer.msg('内容不能为空');
                return false;
            }
            if(!img){
                layer.msg('缩略图不能为空');
                return false;
            }
            var url = '/index/message/feedBackInsert';
            $.get(url,{
                    'message_content' : content,
                    'message_img' : img
                }, function (xhr){
                    if(xhr){
                        layer.msg('感谢您的反馈', {icon:1,time: 1000});
                        setTimeout(function () {
                            location.href='/index/index/index'
                        },1500)
                    }else{
                        layer.msg('反馈失败!',{icon:2,time:2000});
                        return false;
                    }
                }
            );
        })

        //响应文件添加成功事件
        $('#inputfilea').change(function(){
            var form_data = new FormData($("#newForm")[0]);
            var files = $(this)[0].files[0];
            form_data.append('file',files);

            $.ajax({
                url:'/api/Upload/form',
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
                error:function(){
                    layer.msg('请选择图片格式文件或图片不能大于5MB');
                    return ;
                }
            });
        });

    </script>
</html>