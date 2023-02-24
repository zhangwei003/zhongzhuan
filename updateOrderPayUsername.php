<?php
include_once './tools2.php';
$data['trade_no'] = $_POST['trade_no'];
$data['pay_username'] = $_POST['pay_username'];
$ret = httpRequest('http://'.decrypt($_POST['key']).'/api/pay/updateOrderPayUsername', 'post', $data);
print_r($ret);exit;


