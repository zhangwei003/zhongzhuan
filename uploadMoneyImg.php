<?php
include_once './tools2.php';
header("Content-type:application/json");
$data['sn'] = $_POST['sn'];
$data['image_path'] = $_POST['image_path'];
$ret = httpRequest('http://'.decrypt($_POST['key']).'/api/pay/uploadMoneyImg', 'post', $data);
print_r($ret);exit;
