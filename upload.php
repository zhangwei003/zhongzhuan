<?php
    header("Access-Control-Allow-Origin:*");
    header("Content-type:application/json");

     $date = date('Ymd');
    $path="./upload/" . $date . '/';
    if(!file_exists($path))
    {
        mkdir("$path", 0700, true);
    }


    if (empty( $_FILES["image"])){
        echo json_encode(['code' => 0, 'msg' => '上传图片为空']);
        die();
    }

    $chRet = checkHex($_FILES["image"]['tmp_name']);

    if ($chRet != 'ok'){
        echo json_encode(['code' => 0, 'msg' => $chRet]);
        die();
    }

    $tp = array("image/gif","image/jpeg","image/jpg","image/png");

    if(!in_array($_FILES["image"]["type"],$tp))
    {
        echo json_encode(['code' => 0, 'msg' => '图片格式不合法']);
        die();
    }

    $ext =  pathinfo( $_FILES["image"]["name"], PATHINFO_EXTENSION);
    $allowExts =  ['jpg','png','jpeg'];

    if (!in_array($ext,$allowExts)){
        echo json_encode(['code' => 0, 'msg' => '图片格式不合法']);
        die();
    }

    $image_size = $_FILES['image']['size']/1024/1024;

    if (  $image_size > 20  ){
        echo json_encode(['code' => 0, 'msg' => '图片大小不能超过20MB']);
        die();
    }



    if($_FILES["image"]["name"])
    {
        $filename = md5(microtime(true)) . '.' . $ext;
        $file2 = $path.$filename;

        $flag=1;
    }
    $result = false;
    $flag && $result=move_uploaded_file($_FILES["image"]["tmp_name"],$file2);

    if($result)
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

        $data = base64_encode(openssl_encrypt($http_type . $_SERVER['HTTP_HOST'] .  '/upload/' . $date .'/' . $filename,"AES-128-CBC",'f3a59b69324c831e',true,'7fc7fe7d74f4da93'));


        echo json_encode(['code' => 1, 'msg' => '上传成功', 'data' => $data]);

    } else {
        echo json_encode(['code' => 0, 'msg' => '上传失败']);
    }

    function checkHex($img) {
        $status = 0;
        $tips = array(
            "0" => 'ok',
            "1" => '图片上传为空',
            "2" => "文件有毒",
        );
        if (file_exists($img)) {
            $resource = fopen($img, 'rb');
            $fileSize = filesize($img);
            fseek($resource, 0);
            if ($fileSize > 512) { // 取头和尾
                $hexCode = bin2hex(fread($resource, 512));
                fseek($resource, $fileSize - 512);
                $hexCode .= bin2hex(fread($resource, 512));
            } else { // 取全部
                $hexCode = bin2hex(fread($resource, $fileSize));
            }
            fclose($resource);
            /* 匹配16进制中的 <% ( ) %> */
            /* 匹配16进制中的 <? ( ) ?> */
            /* 匹配16进制中的 <script | /script> 大小写亦可 */
            if (preg_match("/(3c25.*?28.*?29.*?253e)|(3c3f.*?28.*?29.*?3f3e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is", $hexCode)) {
                $status = 2;
            }
        } else {
            $status = 1;
        }
        return $tips[$status];
    }
