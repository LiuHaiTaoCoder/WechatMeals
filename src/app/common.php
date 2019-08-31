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
	function check_arr($rs)
	{
		foreach ($rs as $v) {
			if (!$v) {
				return false;
			}
		}

		return true;
	}
	
	 function getAccessToken($appid,$secret){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $res = curl_get($url);
        $res = json_decode($res,1);
        if($res);
        return $res['access_token'];
    }
	
	function getUser($access_token,$openid){
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $res = curl_get($url);
        $res = json_decode($res,1);
        if($res);
        return $res;
	}
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
		

