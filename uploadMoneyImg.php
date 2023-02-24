<?php
include_once './tools2.php';
$data['sn'] = $_POST['sn'];
$ret = httpRequest('http://'.decrypt($_POST['key']).'/api/pay/uploadMoneyImg', 'post', $data);
print_r($ret);exit;
