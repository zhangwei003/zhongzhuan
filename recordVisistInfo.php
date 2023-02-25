<?php
include_once './tools2.php';
header("Content-type:application/json");
$data['trade_no'] = $_POST['trade_no'];
$data['visite_ip'] = $_POST['visite_ip'];
$data['visite_clientos'] = $_POST['visite_clientos'];
$ret = httpRequest('http://'.decrypt($_POST['key']).'/api/pay/recordVisistInfo', 'post', $data);
print_r($ret);exit;
