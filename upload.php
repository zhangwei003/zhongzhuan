<?php
header("Access-Control-Allow-Origin:*");
header("Content-type:application/json");
     $date = date('Ymd');
    $path="./upload/" . $date . '/';
    if(!file_exists($path))
    {
        mkdir("$path", 0700, true);
    }

    $tp = array("image/gif","image/jpeg","image/jpg","image/png");

    if(!in_array($_FILES["image"]["type"],$tp))
    {
        echo json_encode(['code' => 0, 'msg' => '图片格式不合法']);
        die();
    }

    if($_FILES["image"]["name"])
    {
        $file1=$_FILES["image"]["name"];
        $ext =  pathinfo($file1, PATHINFO_EXTENSION);
        $filename = md5(microtime(true)) . '.' . $ext;
        $file2 = $path.$filename;

        $flag=1;
    }
    $result = false;
    $flag && $result=move_uploaded_file($_FILES["image"]["tmp_name"],$file2);

    if($result)
    {
        echo json_encode(['code' => 1, 'msg' => '上传成功', 'data' => '/upload/' . $date .'/' . $filename]);

    } else {
        echo json_encode(['code' => 0, 'msg' => '上传失败']);
    }

