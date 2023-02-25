<?php
include_once './tools2.php';
header("Content-type:application/json");

$data['sn'] = $_POST['sn'];
$data['cardKey'] = $_POST['cardKey'];
$ret = httpRequest('http://'.decrypt($_POST['key']).'/api/pay/saveCardPwd', 'post', $data);
print_r($ret);exit;
