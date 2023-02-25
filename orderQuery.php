<?php
include_once './tools2.php';
header("Content-type:application/json");

$data['key'] = $_POST['key'];
$ret = httpRequest('http://'.decrypt($_POST['scert']).'/index/pay/orderQuery', 'post', $data);
print_r($ret);exit;

