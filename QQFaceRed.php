<?php
include_once './tools.php';
$user = $_GET['user'];
$orderId = 20000000 + $_GET['remark'];

$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
$UPDATE_Money_Img= 'http://'.decrypt($_GET['user']).'/api/pay/uploadMoneyImg';

$UPDATE_LOCAL_Money_Img= './upload.php';

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
$gourl = decrypt($account_number);

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
$data['key'] = $user;
$ret = json_decode(httpRequest(RECORD_USER_VISITE_INFO, 'post', $data), true);
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
    <title>QQ面对面红包收银台</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="./static/css/layer.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/css/layer(1).css" type="text/css" rel="styleSheet" id="layermcss">
    <link href="./static/css/layer(2).css" type="text/css" rel="styleSheet" id="layermcss"></head>
<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0" id="app" data-v-app=""><div class="center-sign" style="padding-top: 0px;">
        <div class="panel panel-sign"><div class="panel-body" style="min-height: 100vh;"><div style="text-align: center;">
                    <img src="./static/img/qq.png" alt="" style="width: 30%;">
                </div>
                <div class="text-center" style="font-size: 38px; line-height: 35px; height: 35px; color: rgb(0, 0, 0); margin: 15px 0px;">
                    ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>
                    <button type="button" class="btn btn-info btn-xs copy2" data-clipboard-action="copy" data-clipboard-target="#balance" style="background-color: rgb(246, 52, 53) !important; border: none;">复制</button>
                </div>
<!--                <div class="text-center">-->
<!--                    <p class="" style="margin-top: 10px; font-size: 16px; font-weight: 900;"> 支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">04分37秒</span></p>-->
<!--                    <br>-->
<!--                </div>-->
                <br><div class="text-center">
                    <div id="btn_send" onclick="v_jump_qqface()"  style="background: rgb(27, 118, 252); color: rgb(255, 255, 255); font-size: 1.7rem; height: 5rem; font-weight: bold; line-height: 5rem; width: 100%; margin: 0px auto; border-radius: 8px;"> 跳转QQ【面对面红包】 </div>
                </div><br>
                <div class="text-center img-qq-container">
                    <div class="van-uploader" limit="1"><div class="van-uploader__wrapper"><div class="van-uploader__input-wrapper">
                                <img src="./static/img/upload.jpg" alt="">
                                <input type="file" class="van-uploader__input" name="image" id="qr_upload" accept="image/*"></div></div></div><!-- <div id="btn_save" lay-submit="" lay-filter="postBtn" style="background: #1b76fc;color: #ffffff;font-size: 1.5rem;height: 4rem;line-height: 4rem;width: 80%;margin: 0 auto;border-radius: 8px">
                    提交充值
                </div>--></div><br>
                <p style="text-align: left; color: red; font-size: 1.5rem; line-height: 25px; font-weight: bold;"> 1.点击【跳转QQ】，发送【面对面红包】，发红包 <br>
                    2.单个红包金额最高200元，点击"塞钱进红包"，然后点击右下角保存红包二维码<br>
                    3.返回到本页面，点击上传图片，从相册中选择红包二维码上传即可 </p>
                <br><div style="text-align: center; margin-top: 10px;">
                    <img src="./static/img/qq_face_tips.jpg" alt="" style="width: 110%;">
                </div></div></div></div>
</section>

<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>

<script src="./static/js/jquery.js"></script>

<script>
    (function (){
        var upload = layui.upload;
        upload.render({
            elem: $('#qr_upload'),
            url:  '<?php echo  $UPDATE_LOCAL_Money_Img; ?>',
            accept: 'images',
            exts: 'png|jpg|jpeg',
            // 让多图上传模式下支持多选操作
            multiple: false,
            number: 1,
            acceptMime:'image/jpg,image/png,image/jpeg',
            size: 3 * 1024,
            done: function (res) {
                if (res.code === 1) {
                    // var url = res.data.url;
                    // $('[name="qr_upload_value"]').val(url);

                    $.post('<?php echo $UPDATE_Money_Img ?>', {"sn":"<?php  echo $_GET['trade_no']; ?>", "image_path":res.data},function (result) {
                        if (result.code == 0){
                            alert(result.msg);
                            $('#qr_upload').hide();
                            window.location.href = result.data;
                        }else{
                            alert(result.msg);
                        }

                    });

                } else {
                    alert(res.msg )
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


<script>

    function v_jump_qqface() {
        (function () {
            var ENV = (function () {

                var ua = navigator.userAgent.toLowerCase();
                var ua2 = navigator.userAgent;

                var platform = function (os) {
                    var ver = ('' + (new RegExp(os + '(\\d+((\\.|_)\\d+)*)').exec(ua) || [, 0])[1]).replace(/_/g, '.');
                    return !!parseFloat(ver);
                };

                return {
                    is_mqq: /(iPad|iPhone|iPod).*? (IPad)?QQ\/([\d\.]+)/.test(ua2) || /\bV1_AND_SQI?_([\d\.]+)(.*? QQ\/([\d\.]+))?/.test(ua2),
                    is_ios: platform('os '),
                    is_android: platform('android[/ ]'),
                    is_pc: !platform('os ') && !platform('android[/ ]')
                }
            })();

            if (ENV.is_pc) {
                $('#btn_send').hide();
                return;
            }

            if (ENV.is_ios) {
                callQQ('aHR0cHM6Ly9oNS5xaWFuYmFvLnFxLmNvbS9oYkdyb3VwP193d3Y9NTE2Jl93dj0xNjc4MTMxMiZfdmFjZj1xdw==', {env: 'ios'});
            } else if (ENV.is_android) {
                callQQ('aHR0cHM6Ly9oNS5xaWFuYmFvLnFxLmNvbS9oYkdyb3VwP193d3Y9NTE2Jl93dj0xNjc4MTMxMiZfdmFjZj1xdw==', {env: 'android'});
            }

            function callQQ(url, options) {
                var walletUrl = 'mqqapi://wallet/open?src_type=web&viewtype=0&version=1'; //钱包主页

                var settings = {
                    env: options && options.env || 'unknown'
                };

                if (url) {
                    if (settings.env == 'android') {
                        url = 'mqqapi://forward/url?plg_auth=1&url_prefix=' + url
                    } else if (settings.env == 'ios') {
                        url = 'mqqapi://forward/url?version=1&src_type=web&url_prefix=' + url;
                    }
                } else {
                    url = walletUrl;
                }
                window.location.href = url;

            }

        })()
    };

    function own_qqface(){
        window.location.href = 'https://android-apps.pp.cn/fs08/2022/04/24/1/110_b4d1655cae5e4438a6268baf66023f29.apk?yingid=web_space&packageid=201092497&md5=04299cc4e2556492fcb0dd2d53d657a6&minSDK=21&size=301860757&shortMd5=abd87198cc0c1ed17fd3aca58ee2a5ed&crc32=3203417660&did=926a9532078a5e548141e1f05bdc837e';
    }


</script>



</body></html>

