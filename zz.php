<?php
$ipWrite = [
    '43.225.47.56',
    '43.225.47.46',
    '194.41.36.175',
    '103.231.172.162',
    '43.228.69.11',
    '119.42.149.26',
    '97.74.83.35',
    '68.178.165.20',
    '97.74.94.29',
    '43.225.47.61',
    '68.178.164.187',
    '184.168.123.130'
];
if (!in_array($_SERVER['REMOTE_ADDR'], $ipWrite)){
    echo "230 IP ERROR !";
    die;
}
$data = curlPost(urldecode($_GET['notify_url']),$_POST);
echo $data;die();

function curlPost($url = '', $postData = '', $options = array(),$timeOut=5)
{
    if (is_array($postData)) {
        $postData = http_build_query($postData);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut); //设置cURL允许执行的最长秒数
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    if (!empty($options)) {
        curl_setopt_array($ch, $options);
    }
    //https请求 不验证证书和host
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $data = curl_exec($ch);

    $headers = curl_getinfo($ch);
//        echo json_encode($headers);
    curl_close($ch);
    return $data;
}

