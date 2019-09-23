<?php
// +-------------------------------------------------
header('Content-Type:text/html;charset=utf-8');
// 应用公共文件
//生成订单号16位
function getOrderNo(){
    return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 14), 1))), 0, 8);
}
//生成退款单号(16位)
function getRefundNo(){
    $orderid = (date('y') + date('m') + date('d')) . str_pad((time() - strtotime(date('Y-m-d'))), 5 ,0,
            STR_PAD_LEFT) . substr(microtime(),2,6) . sprintf('%03d',rand(0,999));
    return $orderid;
}
/*
* 检查是否为数组函数
* return Boolean
* */
function check_arr($rs)
{
    foreach ($rs as $v) {
        if (!$v) {
            return false;
        }
    }
    return true;
}
//获取缓存文件存放的目录
function delete_dir_file($dir_name)
{
    $result = false;
    if(is_dir($dir_name)){ //检查指定的文件是否是一个目录
        if ($handle = opendir($dir_name)) {   //打开目录读取内容
            while (false !== ($item = readdir($handle))) { //读取内容
                if ($item != '.' && $item != '..') {
                    if (is_dir($dir_name . DS . $item)) {
                        delete_dir_file($dir_name . DS . $item);
                    } else {
                        unlink($dir_name . DS . $item);  //删除文件
                    }
                }
            }
            closedir($handle);  //打开一个目录，读取它的内容，然后关闭
            if (rmdir($dir_name)) { //删除空白目录
                $result = true;
            }
        }
    }
    return $result;
}
/*
 * 拉取微信用户信息方法
 * return 用户信息以及openid
 * @param appid
 * @param appsecret
 * */
function getUserMenthod($code)
{
    $appid = "wxe22747c8d4fe0613";
    $appsecret = "e903ecd6e458fa60dd0485d1298bbbd6";
    //Get access_token
    $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
    $access_token_json = https_request($access_token_url);//自定义函数
    $access_token_array = json_decode($access_token_json,true);//对 JSON 格式的字符串进行解码，转换为 PHP 变量，自带函数
    //获取access_token
    $access_token = $access_token_array['access_token'];//获取access_token对应的值
    //var_dump($access_token);die;
    //获取openid
    $openid = $access_token_array['openid'];//获取openid对应的值

    //Get user info
    //$userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
    $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
    $userinfo_json = https_request($userinfo_url);
    $userinfo_array = json_decode($userinfo_json,ture);
    return $userinfo_array;
}
/*
 * 发送https请求
 * */
function https_request($url)//自定义函数,访问url返回结果
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl,  CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)){
        return 'ERROR'.curl_error($curl);
    }
    curl_close($curl);
    return $data;
}
function curl_get_contents($url){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                //设置访问的url地址
    // curl_setopt($ch,CURLOPT_HEADER,1);               //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);               //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //用户访问代理 User-Agent
    curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_HOST']);        //设置 referer
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);          //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
    $r=curl_exec($ch);
    curl_close($ch);
    return $r;
}
/*
 * 获取微信 AccessToken
 * 有效时长为2小时，超过则重新获取
 * return accesstoken
 * */
function getAccessToken($appid,$secret){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
    $res = curl_get($url);
    $res = json_decode($res,1);
    if($res);
    return $res['access_token'];
}
/*
 * 获取微信用户的基本信息
 * return 数组格式的结果集
 * */
function getUser($access_token,$openid){
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
    $res = curl_get($url);
    $res = json_decode($res,1);
    if($res);
    return $res;
}
/*
 * 发送curl请求
 * return 请求的内容
 * */
function curl_get($url) {
    $headers = array('User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36');
    $oCurl = curl_init();
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    curl_setopt($oCurl, CURLOPT_TIMEOUT, 20);
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if(intval($aStatus["http_code"])==200){
        return $sContent;
    }else{
        return false;
    }
}

/**
 *数字金额转换成中文大写金额的函数
 *String Int $num 要转换的小写数字或小写字符串
 *return 大写字母
 *小数位为两位
 **/
function num_to_rmb($num)
{
    $c1 = "零壹贰叁肆伍陆柒捌玖";
    $c2 = "分角元拾佰仟万拾佰仟亿";

    //精确到分后面就不要了，所以只留两个小数位
    $num = round($num, 2);
    //将数字转化为整数
    $num = $num * 100;
    if (strlen($num) > 10) {
        return "金额太大，请检查";
    }
    $i = 0;
    $c = "";
    while (1) {
        if ($i == 0) {
            //获取最后一位数字
            $n = substr($num, strlen($num) - 1, 1);
        } else {
            $n = $num % 10;

        }
        //每次将最后一位数字转化为中文
        $p1 = substr($c1, 3 * $n, 3);
        $p2 = substr($c2, 3 * $i, 3);

        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
            $c = $p1 . $p2 . $c;
        } else {
            $c = $p1 . $c;
        }
        $i = $i + 1;
        //去掉数字最后一位了
        $num = $num / 10;
        $num = (int)$num;
        //结束循环
        if ($num == 0) {
            break;
        }
    }

    $j = 0;
    $slen = strlen($c);
    while ($j < $slen) {
        //utf8一个汉字相当于3个字符
        $m = substr($c, $j, 6);
        //处理数字中很多0的情况,每次循环去掉一个汉字“零”
        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
            $left = substr($c, 0, $j);
            $right = substr($c, $j + 3);
            $c = $left . $right;
            $j = $j - 3;
            $slen = $slen - 3;
        }
        $j = $j + 3;
    }

    //这个是为了去掉类似23.0中最后一个“零”字
    if (substr($c, strlen($c) - 3, 3) == '零') {
        $c = substr($c, 0, strlen($c) - 3);
    }

    //将处理的汉字加上“整”
    if (empty($c)) {
        return "零元整";
    } else {
        return $c . "整";
    }

}


