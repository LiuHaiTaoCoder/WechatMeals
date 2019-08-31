<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"/www/wwwroot/diancang.cybaike.com/public/../app/api/view/wxpay/pay.html";i:1566560929;}*/ ?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付</title>
</head>
<body>
<script type="text/javascript">
    //调用微信JS api 支付
	function jsApiCall()
    {
        WeixinJSBridge.invoke(
             'getBrandWCPayRequest', {
	         "appId":"<?php echo $data['appId']; ?>",     //公众号名称，由商户传入     
	         "timeStamp":"<?php echo $data['timeStamp']; ?>",//时间戳，自1970年以来的秒数     
	         "nonceStr":"<?php echo $data['nonceStr']; ?>", //随机串     
	         "package":"<?php echo $data['package']; ?>",   //支付的ID
	         "signType":"<?php echo $data['signType']; ?>", //微信签名方     
	         "paySign":"<?php echo $data['paySign']; ?>" //微信签名 
			},
            function(res){
                WeixinJSBridge.log(res.err_msg);
                //document.getElementById("test").innerHTML=JSON.stringify(res);
                if(res.err_msg == "get_brand_wcpay_request:ok"){
                    window.location.href = '/index/index/index';
                }else if(res.err_msg == "get_brand_wcpay_request:cancel"){
                    window.location.href = '/index/order/myOrder/id/'+<?php echo $id; ?>;
                }
            }
        );
    }
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', jsApiCall);
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    }else {
        jsApiCall();
    }
 
</script>
</body>
</html>
