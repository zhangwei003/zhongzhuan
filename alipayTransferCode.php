<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$is_lock_code = $_GET['is_lock_code'];
unset($_GET['is_lock_code']);
if ($is_lock_code == 2){
    $UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsernameNoLockCode';
}else{
    $UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
}
$UPDATE_Money_Img= 'http://'.decrypt($_GET['user']).'/api/pay/uploadMoneyImg';
$is_pay_name = $_GET['is_pay_name'];
unset($_GET['is_pay_name']);
unset($_GET['remark']);
unset($_GET['user']);
$account_name = addslashes($_GET['account_name']);
$bank_name = addslashes($_GET['bank_name']);
$account_number = addslashes($_GET['account_number']);
$trade_no = addslashes($_GET['trade_no']);
$order_pay_price = addslashes($_GET['order_pay_price']);
$sign = addslashes($_GET['sign']);

//only get params
$is_bzk=$_GET['is_bzk'];
$paramsKeys = ['account_name', 'is_bzk','bank_name', 'account_number', 'trade_no', 'order_pay_price', 'sign'];
//$gourl = $_GET['gourl'];
//unset($_GET['gourl']);
$keyDifs = array_diff(array_keys($_GET), $paramsKeys);
if ($keyDifs) {
    die("访问异常1");
}
foreach ($paramsKeys as $key => $val) {
    if (!array_key_exists($val, $_GET) || empty($_GET[$val])) {
//        die("访问异常2");
    }
}

//验证签名
$inner_transfer_secret = 'g8CZvkqwwFRmKyloOAc2hLAZgZg8Ahcz';
$sign = getSign(array_merge($_GET, ['key' => $inner_transfer_secret]));
if ($sign !== $_GET['sign']) {
    //  die("访问异常3");
}
//记录用户访问相关信息以及安全季校验
$data['trade_no'] = $_GET['trade_no'];
$data['visite_ip'] = getRealIp();
$data['visite_clientos'] = clientOS();
$ret = json_decode(httpRequest($returl, 'post', $data), true);
if ($ret['code'] != 1) {
}
?>

<!DOCTYPE html>
<!-- saved from url=(0114)http://pay5.wuhengshop.com/c/api/pay?osn=2023010223015971952147330&t=1672671719&k=9f5baa1ca4d7014368e8b7b564bc84aa -->
<html class="fixed js flexbox flexboxlegacy csstransforms csstransforms3d no-overflowscrolling" style=""><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./static/css/bootstrap.css">
    <link rel="stylesheet" href="./static/css/font-awesome.css">
    <!-- <link rel="stylesheet" href="/static/pay/magnific-popup.css?v=20221112003800001">-->
    <!--<link rel="stylesheet" href="/static/pay/datepicker3.css?v=20221112003800001">-->
    <!-- Theme CSS -->
    <link rel="stylesheet" href="./static/css/theme.css">
    <!-- Skin CSS -->
    <!-- <link rel="stylesheet" href="/static/pay/default.css?v=20221112003800001">-->
    <!-- Theme Custom CSS -->
    <!-- <link rel="stylesheet" href="/static/pay/theme-custom.css?v=20221112003800001">-->

    <!-- Head Libs -->
    <!-- <script src="/static/pay/modernizr.js?v=20221112003800001"></script>-->
    <script src="./static/js/clipboard.js"></script>


    <link rel="stylesheet" href="./static/css/vant.css">
    <script src="./static/js/vue.js"></script>
    <script src="./static/js/vant.min.js"></script>

    <style>
        .login span {
            line-height: 50px;
            font-weight: 900;
        }

        .btn-info{
            background: #027AFF !important;
            border-color: #027AFF !important;
        }
        .kltips {
            font-size: 1.8rem;
            color: #f30;
            padding: 1rem;
            text-align: center
        }
        .center-sign .warmTips{color: #f58807;border-top: 1px solid #f2f2f2;margin-top: 1.2rem;padding: 0 4%;line-height: 1.7rem;}
        .center-sign .warmTips h3{padding: 1rem 0 0.5rem;}
        .center-sign .warmTips p{}

        .body-sign .panel-sign .panel-body {
            background: #FFF;
            border-top: 5px solid #cccccc;
            border-radius: 5px 0 5px 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            padding: 20px 33px 15px;
        }
        #layui-m-layer0 .layui-m-layerbtn {display:box;display:-moz-box;display:-webkit-box;width:100%;
            height:50px;line-height:50px;font-size:0;border-top:1px solid #D0D0D0;
            background-color:#1b76fc;
        }
        #layui-m-layer0 .layui-m-layercont {
            padding: 30px 20px;
            line-height: 22px;
            text-align: left;
        }
        .layui-upload-file {
            display: none!important;
            opacity: .01;
            filter: Alpha(opacity=1)
        }

    </style>
    <title>支付宝转账码收银台</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link href="./static/layui/css/layui.css" rel="stylesheet" />
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">

<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0" id="app" data-v-app=""><div class="center-sign" style="padding-top: 0px;">
        <div class="panel panel-sign"><div class="panel-body" style="min-height: 100vh;"><div style="text-align: center;">
                    <img src="./static/img/alipay_logo.png" alt="" style="width: 30%;">
                </div>
                <div class="text-center" style="font-size: 38px; line-height: 35px; height: 35px; color: rgb(0, 0, 0); margin: 15px 0px;">
                    ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>
                    <button type="button" class="btn btn-info btn-xs copy2" data-clipboard-action="copy" data-clipboard-target="#balance" style="background-color: rgb(246, 52, 53) !important; border: none;">复制</button>
                </div>
                <br>
                <div class="text-center img-qq-container">
                    <div class="van-uploader" limit="1"><div class="van-uploader__wrapper"><div class="van-uploader__input-wrapper">
                                <img src="./static/img/upload.jpg" alt="">
                                <input type="file" class="van-uploader__input" name="image" id="qr_upload" accept="image/*"></div></div></div><!-- <div id="btn_save" lay-submit="" lay-filter="postBtn" style="background: #1b76fc;color: #ffffff;font-size: 1.5rem;height: 4rem;line-height: 4rem;width: 80%;margin: 0 auto;border-radius: 8px">
                    提交充值
                </div>--></div><br>
                <p style="text-align: left; color: red; font-size: 1.5rem; line-height: 25px; font-weight: bold;"> 1.打开支付宝首页点击转账，选择转账码 <br>
                    2.输入金额，领取方式选择验证我的姓名后即可领取，点击转账，转账成功后点击保存图片<br>
                    3.返回到本页面，点击上传图片，从相册中选择该二维码上传即可 </p>
                <br><div style="text-align: center; margin-top: 10px;">
                    <img src="./static/img/alipayTransferCode_tip.jpg" alt="" style="width: 100%;">
                </div></div></div></div>
</section>

<script src="./static/layui/layui.js"></script>
<script src="./static/js/layer.js"></script>

<script src="./static/js/jquery.js"></script>
<script>
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer;
        var form = layui.form;


        layer.open({
            content:
                '<p style="color: red;text-align: center;font-size: 18px;font-weight: bold;margin-bottom: 5%;">请填写付款人姓名</p>' +
                '<p style="text-align: center"><input id="pay_username" placeholder="请输入付款人姓名" style="border: 1px solid #e6e6e6;" type="text" class="layui-input" ></p>'
            , btn: '确定'
            ,title:'请输入付款人姓名'
            , closeBtn: 0
            , shadeClose: false
            , yes: function (index) {
                pay_username = $('#pay_username').val();
                if (pay_username == '') {
                    alert('请输入付款人姓名');
                    return false;
                }
                $.post('<?php echo $UPDATE_PAY_USER_NAME ?>',{trade_no: '<?php echo $_GET['trade_no'];?>', pay_username: pay_username,}, function (data) {
                    if (data.code != 1) {
                        alert(data.msg);
                        return false;
                    }
                    layer.msg('提交成功', {icon: 1, time: 1500})
                }, 'json')
            }
        });
    })
</script>
<script>
    (function (){
        var upload = layui.upload;
        upload.render({
            elem: $('#qr_upload'),
            url:  '<?php echo $UPDATE_Money_Img; ?>',
            accept: 'images',
            exts: 'png|jpg|jpeg',
            // 让多图上传模式下支持多选操作
            multiple: false,
            number: 1,
            acceptMime:'image/jpg,image/png,image/jpeg',
            size: 3 * 1024,
            data:{
                "sn":"<?php  echo $_GET['trade_no']; ?>",
            },
            done: function (res) {
                if (res.code === 0) {
                    // var url = res.data.url;
                    // $('[name="qr_upload_value"]').val(url);
                    alert(res.msg);
                    $('#qr_upload').hide();
                    window.location.href = res.data;
                } else {
                    alert("上传失败" )
                }
            }
        });
    })();

</script>

<script>

    var clipboard6 = new Clipboard('.copy2');
    clipboard6.on('success', function (e) {
        layer.open({
            content: "复制成功",
            skin: "msg",
            time: 3,
        });

    });
    clipboard6.on('error', function (e) {
        layer.open({
            content: "复制失败",
            skin: "msg",
            time: 3,
        });

    });

    var  order_s = "0";
    var pad = function(num){
        if (num < 10){
            num =  '0' + num;
        }
        return num;
    }

</script>


</body></html>
