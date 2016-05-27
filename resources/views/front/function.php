<?php

//获取用户代理名称 ptimeios,ptimeandroid,wechat,mqq,other
function getUserAgent(){
	if(     strpos($_SERVER['HTTP_USER_AGENT'], 'PTime.IOSWeb') !== false):
	    $userAgent = "ptimeios";
	elseif( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false):
	    $userAgent = "wechat";
	elseif( strpos($_SERVER['HTTP_USER_AGENT'], 'PTime.AndroidWeb') !== false):
	    $userAgent = "ptimeandroid";
	elseif( strpos($_SERVER['HTTP_USER_AGENT'], 'MQQBrowser')!== false):
	    $userAgent = "mqq";
	else:
	    $userAgent = "other";
	endif;
	return $userAgent;
}

//根据用户登录状态构造链接，需用户登录的链接使用
function handleUrl($url,$need_token=false){
    if ($need_token  && empty($_SESSION['token']))
        $url = env('PTIME_URL').'/oauth2/auth?client_id='.env('CLIENT_ID').'&redirect_uri='.urlencode('http://'.$_SERVER['HTTP_HOST'].$url).'&response_type=token'; 
    return $url;
}

//根据REQUEST_URI判断是否返回current
function handleCurrent($string){
	if(is_string($string)) $string = [$string];
	foreach ($string as $value) {
		if(strpos($_SERVER['REQUEST_URI'], $value))
			return 'current';
		if($value == "/" && $_SERVER['REQUEST_URI'] == "/")
			return 'current';
	}
	return '';
}