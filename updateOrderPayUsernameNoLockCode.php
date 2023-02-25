<?php
include_once './tools2.php';
header("Content-type:application/json");
$data['trade_no'] = $_POST['trade_no'];
$data['pay_username'] = $_POST['pay_username'];
$ret = httpRequest('http://'.decrypt($_POST['key']).'/api/pay/updateOrderPayUsernameNoLockCode', 'post', $data);
print_r($ret);exit;