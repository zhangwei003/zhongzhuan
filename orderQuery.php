<?php
include_once './tools2.php';
if(empty($_POST['scert'])){
    echo 1;die;
}
$a = decrypt($_POST['scert']);

if(strlen($a)>23){
    echo 4;die();
}
$a = str_replace("http://","",$a);
$a = str_replace(".","",$a);
$a = str_replace("/","",$a);
$a = str_replace(":","",$a);
if(strlen($a)>17){
    echo 3;die();
}
if(!is_numeric($a))
{
    echo 5;die();
}
$data['key'] = $_POST['key'];
$ret = httpRequest('http://'.decrypt($_POST['scert']).'/index/pay/orderQuery', 'post', $data);
print_r($ret);exit;

