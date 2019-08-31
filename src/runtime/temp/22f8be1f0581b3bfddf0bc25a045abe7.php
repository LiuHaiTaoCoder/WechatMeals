<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/menu/menu.html";i:1566440179;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
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
            <a href="">菜单管理</a>
            <a>
              <cite>套餐分类</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <!-- <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>-->
                    <button class="layui-btn" onclick="xadmin.open('添加套餐','/admin/menu/menuAdd')"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="name"  placeholder="请输入套餐名" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>套餐名</th>
                            <th style="width: 12%">套餐缩略图</th>
                            <th>价格/餐</th>
                            <th>简介</th>
                            <th>加入时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                        <?php if(is_array($res) || $res instanceof \think\Collection || $res instanceof \think\Paginator): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['menu_id']; ?></td>
                            <td><?php echo $vo['menu_name']; ?></td>
                            <td><img src="<?php echo $vo['menu_img']; ?>" alt=""></td>
                            <td><?php echo $vo['menu_price']; ?></td>
                            <td><?php echo $vo['menu_intro']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td class="td-status">
                            <?php if($vo['menu_status'] == '1'): ?>
                                <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span></td>
                            <td class="td-manage">
                                <a onclick="member_stop(this,'<?php echo $vo['menu_id']; ?>')" href="javascript:;"  title="停用">
                                    <i class="layui-icon">&#xe601;</i>
                                </a>
                                <?php else: ?>
                                <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span>
                            <td class="td-manage">
                                <a onclick="member_stop(this,'<?php echo $vo['menu_id']; ?>')" href="javascript:;"  title="启用">
                                    <i class="layui-icon">&#xe601;</i>
                                </a>
                             <?php endif; ?>
                                <a title="编辑"  onclick="xadmin.open('修改菜单','/admin/menu/menuEdit/id/<?php echo $vo['menu_id']; ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="删除" onclick="member_del(this,'<?php echo $vo['menu_id']; ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
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
<script>
    layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var form = layui.form;

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });
    });
    /*启用-停用*/
    function member_stop(obj,id){
        var url = '/admin/menu/menuStatus/id/'+id;
        layer.confirm('确认要停用或启用吗？',function(index){
            if($(obj).attr('title')=='停用'){
                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');
                $.get(url, {'status' : 0}
                    ,function (xhr) {
                        if(xhr){
                            $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                            layer.msg('已停用!',{icon: 5,time:2000});
                            window.location.reload();
                        }else{
                            layer.msg('停用失败');
                        }
                    });

            }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');
                $.get(url, {'status' : 1}
                    ,function (xhr) {
                        if(xhr){
                            $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                            layer.msg('已启用!',{icon: 1,time:2000});
                            window.location.reload();
                        }else{
                            layer.msg('启用失败');
                        }
                    });
            }
        });
    }
    /*删除*/
    function member_del(obj,id){
        layer.confirm('确认要删除吗,删除后无法恢复哦',function(index){
            var url = '/admin/menu/menuDel/id/'+id;
            $.get(url, {}, function (xhr){
                    if(xhr){
                        //发异步删除数据
                        $(obj).parents("tr").remove();
                        layer.msg('已删除!',{icon:1,time:1000});
                    }else{
                        layer.msg('删除失败!',{icon:2,time:1000});
                    }
                }
            )
        });
    }

  /*  function delAll (argument) {

        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
    }*/
</script>

</html>