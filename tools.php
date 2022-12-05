<?php
define('UPDATE_PAY_USER_NAME','http://97.74.84.109/api/pay/updateOrderPayUsername');
define('RECORD_USER_VISITE_INFO','http://97.74.84.109//api/pay/recordVisistInfo');
define('AES_SECRET_KEY', 'f3a59b69324c831e');
define('AES_SECRET_IV','7fc7fe7d74f4da93');

/**
 * 生成签名
 * @param $args
 * @return string
 */
function getSign($args)
{
    ksort($args);
    $mab = '';
    foreach ($args as $k => $v) {
        if ($k == 'sign' || $k == 'key' || $v == '') {
            continue;
        }
        $mab .= $k . '=' . $v . '&';
    }
    $mab .= 'key=' . $args['key'];
    return md5($mab);
}



/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string)
{
    if (!is_array($string)) return addslashes($string);
    foreach ($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;


}

function decrypt($data)
{
    return openssl_decrypt(base64_decode($data), "AES-128-CBC", AES_SECRET_KEY, true, AES_SECRET_IV);
}

 function encrypt($data)
{
    return base64_encode(openssl_encrypt($data,"AES-128-CBC",AES_SECRET_KEY,true,AES_SECRET_IV));

}


/**
 * curl  模拟请求
 * @param $url
 * @param string $method
 * @param null $postfields
 * @param array $headers
 * @param bool $debug
 * @return bool|string
 */
function httpRequest($url, $method = "GET", $postfields = null, $headers = array(), $debug = false)
{
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if ($ssl) {
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);

    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return $response;
    //return array($http_code, $response,$requestinfo);
}


/**
 * 获取用户请求真实ip
 * @return bool|mixed|string
 */
function getRealIp()
{
    $ip=false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}




/**
 * 用户设备类型
 * @return string
 */
function clientOS() {
    return 'windows';
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if(strpos($agent, 'windows nt')) {
        $platform = 'windows';
    } elseif(strpos($agent, 'macintosh')) {
        $platform = 'mac';
    } elseif(strpos($agent, 'ipod')) {
        $platform = 'ipod';
    } elseif(strpos($agent, 'ipad')) {
        $platform = 'ipad';
    } elseif(strpos($agent, 'iphone')) {
        $platform = 'iphone';
    } elseif (strpos($agent, 'android')) {
        $platform = 'android';
        $detail = getClientMobileBrand();//mobile_brand  //mobile_ver
        if($detail){
            $platform.='---'.$detail['mobile_brand'].'-'.$detail['mobile_ver'];
        }
    } elseif(strpos($agent, 'unix')) {
        $platform = 'unix';
    } elseif(strpos($agent, 'linux')) {
        $platform = 'linux';
    } else {
        $platform = 'other';
    }

    return $platform;
}

/**
 * 记录错误日志
 * @param 日志内容 $res
 */
function save_log($res,$level = 'error') {
    $err_date = date("Ym", time());
    //$address = '/var/log/error';
    $address = './'.$level;
    if (!is_dir($address)) {
        mkdir($address, 0700, true);
    }
    $address = $address.'/'.$err_date . '_error.log';
    $error_date = date("Y-m-d H:i:s", time());
    if(!empty($_SERVER['HTTP_REFERER'])) {
        $file = $_SERVER['HTTP_REFERER'];
    } else {
        $file = $_SERVER['REQUEST_URI'];
    }
    if(is_array($res)) {
        $res_real = "$error_date\t$file\n";
        error_log($res_real, 3, $address);
        $res = var_export($res,true);
        $res = $res."\n";
        error_log($res, 3, $address);
    } else {
        $res_real = "$error_date\t$file\t$res\n";
        error_log($res_real, 3, $address);
    }
}


