<?php
include_once  './tools2.php';
$data = new_addslashes($_POST);

//$requesturl = "http://doc.zhifu.com/api/pay/updateOrderPayUsername";
$ret = httpRequest(UPDATE_PAY_USER_NAME, 'post', $data);
print_r($ret);
exit;

