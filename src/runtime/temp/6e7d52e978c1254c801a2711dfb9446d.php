<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/wwwroot/diancang.cybaike.com/public/../app/admin/view/index/welcome.html";i:1566973070;s:69:"/www/wwwroot/diancang.cybaike.com/app/admin/view/index/adminFile.html";i:1566440175;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-后台管理</title>
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
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <blockquote class="layui-elem-quote">欢迎管理员：
                                <span class="x-red"><?php echo $admin_login['admin_name']; ?></span>！当前时间: <span id="nowtime"></span>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">数据统计</div>
                        <div class="layui-card-body ">
                            <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="/admin/order/orderlist" class="x-admin-backlog-body">
                                        <h3>订单数</h3>
                                        <p>
                                            <cite><?php echo $countOrder; ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="/admin/user/parents" class="x-admin-backlog-body">
                                        <h3>会员数</h3>
                                        <p>
                                            <cite><?php echo $countUser; ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="/admin/message/message" class="x-admin-backlog-body">
                                        <h3>反馈数</h3>
                                        <p>
                                            <cite><?php echo $countFeedBack; ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>总消费金额</h3>
                                        <p>
                                            <cite><?php echo $countMoney; ?>元</cite></p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">系统信息</div>
                        <div class="layui-card-body ">
                            <table class="layui-table">
                                <tbody>
                                    <tr>
                                        <th>您的当前IP</th>
                                        <td><?php echo \think\Request::instance()->ip(); ?></td></tr>
                                    <tr>
                                        <th>操作系统</th>
                                        <td>Linux</td></tr>
                                    <tr>
                                        <th>运行环境</th>
                                        <td>Apache/2.4.23 (Win32) OpenSSL/1.0.2j mod_fcgid/2.3.9</td></tr>
                                    <tr>
                                        <th>PHP版本</th>
                                        <td><?php  echo phpversion(); ?></td></tr>
                                    <tr>
                                        <th>MYSQL版本</th>
                                        <td>5.5.53</td></tr>
                                    <tr>
                                        <th>ThinkPHP</th>
                                        <td><?php echo THINK_VERSION; ?></td></tr>
                                    <tr>
                                        <th>后台版本</th>
                                        <td>V2.2</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">开发团队</div>
                        <div class="layui-card-body ">
                            <table class="layui-table">
                                <tbody>
                                    <tr>
                                        <th>版权所有</th>
                                        <td>艺之都网络
                                            <a href="http://www.yizhidou.com/" style="color: #1AA093;" target="_blank">访问官网</a></td>
                                    </tr>
                                    <tr>
                                        <th>开发者</th>
                                        <td>海涛(2365057581@qq.com)</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <style id="welcome_style"></style>
                <div class="layui-col-md12">
                    <blockquote class="layui-elem-quote layui-quote-nm">感谢layui,百度Echarts,jquery,本后台由艺之都提供技术支持。</blockquote></div>
            </div>
        </div>
        </div>
    </body>
    <script>
        function getTime(){
            //获取当前时间
            var date = new Date();
            //格式化为本地时间格式
            var result = date.toLocaleString();
            //获取span
            var times =  document.getElementById("nowtime");
            //将时间写入
            times.innerHTML = result;
        }
        setInterval("getTime()",1000);
    </script>
</html>