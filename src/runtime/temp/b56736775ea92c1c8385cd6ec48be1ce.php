<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"/www/wwwroot/diancang.cybaike.com/public/../app/index/view/index/personal.html";i:1567050348;s:70:"/www/wwwroot/diancang.cybaike.com/app/index/view/index/commonFile.html";i:1566440189;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>个人中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/static/index/style/personal.css">
    <script>
     document.documentElement.style.fontSize = document.documentElement.clientWidth / 3.75 + 'px';
    </script>
    <script src="/static/index/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/static/index/js/layer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/static/index/layui/css/layui.css" />-->

</head>
<body>
   <div class="containbox">
   <div class="top_bg">
      <div class="servebox">
            <a href="/index/index/ContactTheCustomerService/">
         <img src="/static/index/images/service.png" alt="">
      </a>
      </div>
      <div class="contain">
         <div>
            <img  class="headbox" src="<?php echo !empty($user['user_img'])?$user['user_img'] : '/static/index/images/touxiang.png'; ?>" alt="">
         </div>
         
         <h1>欢迎您，<?php echo $user['user_name']; ?>！</h1>
         <div class="personbox">
               <a href="/index/user/personal">
                     <span>个人中心</span>
               </a>
            <img class="than"  src="/static/index/images/xioayu.png" alt="">
         </div>
      </div>
   </div>
   <div class="bgmidd"></div>
   <div class="ordernave">
        <div class="navbox">
           <div class="navelist">
               <?php if(is_array($result) || $result instanceof \think\Collection || $result instanceof \think\Paginator): if( count($result)==0 ) : echo "" ;else: foreach($result as $k=>$vo): ?>
                 <span class="nav <?php if(($k == 0)): ?> act  <?php else: endif; ?>"><?php echo $vo['children_name']; ?></span>
               <?php endforeach; endif; else: echo "" ;endif; ?>
           </div>
           <div class="add"><a href="/index/student/region"><img src="/static/index/images/tianjia.png" alt=""></a></div>
        </div>

       <?php if(is_array($result) || $result instanceof \think\Collection || $result instanceof \think\Paginator): if( count($result)==0 ) : echo "" ;else: foreach($result as $k=>$vo): ?>
        <div class="action <?php if(($k == 0)): ?> active  <?php else: endif; ?>">
           <div class="action_list">
              <div class="actionbox">
                 <a href="/index/meals/meals/id/<?php echo $vo['children_id']; ?>">
                     <img src="/static/index/images/order01.png" >
                     <h1>我要订餐</h1>
                 </a>
              </div>
              <div class="actionbox">
                  <a href="/index/order/myrefund/id/<?php echo $vo['children_id']; ?>">
                        <img src="/static/index/images/order04.png" alt="">
                        <h1>我的退款</h1>
                  </a>
               </div>
           </div>
           <div class="action_list">
               <div class="actionbox">
                   <a href="/index/order/myOrder/id/<?php echo $vo['children_id']; ?>">
                        <img src="/static/index/images/order02.png" alt="">
                        <h1>我的订单</h1>
                   </a>
                  </div>
                  <div class="actionbox">
                     <a href="/index/leave/myLeave/id/<?php echo $vo['children_id']; ?>">
                           <img src="/static/index/images/order05.png" alt="">
                           <h1>我的请假</h1>
                     </a>
                   </div>
           </div>
           <div class="action_list">
               <div class="actionbox">
                    <a href="/index/order/payBack/id/<?php echo $vo['children_id']; ?>">
                        <img src="/static/index/images/order03.png" alt="">
                         <h1>补缴记录</h1>
                    </a>
                  </div>
                  <div class="actionbox">
                      <a href="/index/message/feedBack">
                           <img src="/static/index/images/order06.png" alt="">
                           <h1>意见反馈</h1>
                      </a>
                   </div>
           </div>
        </div>
       <?php endforeach; endif; else: echo "" ;endif; ?>
   </div>
</div>
<script src="/static/index/js/personal.js"></script>
</body>
</html>