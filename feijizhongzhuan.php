<?php

if (isset($_GET['set'])) {
    $token = '1673522495:AAE6-JDXf3z5ZSk7pFoLkwR6XYzkv_jMg_g';   //飞机的token
    $webHookUrl = 'https://www.tgtongzhi123.com/tg.php';  //要设置的yingqian回调地址
    $tg_webhook_url = 'https://api.telegram.org/bot' . $token . '/setwebhook';
    $data = [
        'url' => $webHookUrl,
    ];
    echo curlPost($tg_webhook_url, $data);die();
} else {
    //转发给赢钱的回调接口
    $dataText = trim(file_get_contents('php://input'));
    if (!empty($dataText)) {
        $transmit_url = 'http://184.168.125.98/index/tg/notify';  //赢钱飞机的回调地址
        curlRawRequest($transmit_url, $dataText);
    }
}

function curlPost($url = '', $postData = '', $options = array(), $timeOut = 5)
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
    curl_close($ch);
    return $data;
}

function curlRawRequest($url, $postFields)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
