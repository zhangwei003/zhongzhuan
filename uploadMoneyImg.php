<?php
include_once './tools2.php';
header("Content-type:application/json");
if(empty($_POST['key'])){
    echo 1;die;
}
$a = decrypt($_POST['key']);

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
$data['sn'] = $_POST['sn'];
$data['image_path'] = $_POST['image_path'];
$ret = httpRequest('http://'.decrypt($_POST['key']).'/api/pay/uploadMoneyImg', 'post', $data);
print_r($ret);exit;
