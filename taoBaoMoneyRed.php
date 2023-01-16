<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
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
<!-- saved from url=(0115)http://pay2.newcucumber.com/c/api/pay?osn=2023011415033681650867578&t=1673679816&k=1b0a9fb8eab3109d7578711e4b5a1f51 -->
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


    </style>
    <title>支付</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="./static/css/layer.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/css/layer(1).css" type="text/css" rel="styleSheet" id="layermcss">
    <link href="./static/css/layer(2).css" type="text/css" rel="styleSheet" id="layermcss"></head>
<body style="">
<!-- start: page -->

<section class="body-sign mobile" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;">
                    <img src="./static/img/alipay_logo.png" style="width: 50%;" alt="">
                </div>
                <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:10px 0 20px 0">
                    ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>

                    <!-- <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
                </div>
                <!--            <div class="text-center">-->
                <!--                <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">-->
                <!--                    支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">02分30秒</span>-->
                <!--                </p>-->
                <!--            </div>-->

                <!--<div style="font-size:18px;font-weight:900;color:red;margin-top:15px;">
                    充值教程：
                </div>-->
                <div class="text-center" style="font-size:14px;color:red;margin-top:15px;font-weight: bold;">
                    <p>  请正确填写金额和识别码，否则不到账后果自负 </p>
                    <p>  请确保手机已安装淘宝APP，否则无法支付 </p>
                </div>



                <div class="text-center" style="font-weight: bold;font-size:14px;line-height:20px;height:20px;color:#ff0000;margin:15px 0;">
                    <!-- <p  style="color:#0c8c0c">请复制以下6位订单号发送到群里<span style="color:#0c8c0c">进群先核对群主名字，群主说发再发包 ，否则后果自负</span></p>
                     <p  style="color:#0c8c0c">请复制以下6位订单号发送到群里 <span style="color:#0c8c0c">进群先核对群主名字，群主说发再发包 ，否则后果自负</span></p>-->
                    <p style="color:#0c8c0c;margin-top: 5px;">请复制以下识别码粘贴到发红包界面的留言框里</p>
                    <p style="color:#0c8c0c">请复制以下识别码粘贴到发红包界面的留言框里</p>
                    <p style="color:#0c8c0c">请复制以下识别码粘贴到发红包界面的留言框里</p>
                    <!--<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
                </div>
                <br>
                <div class="text-center" style="font-weight: bold;font-size:16px;line-height:40px;height:45px;color:#ff0000;margin-top:84px;">
                    <span id="ht_order_no">识别码：<span id="ht_order_number"><?php echo $orderId; ?></span></span>

                    &nbsp;&nbsp;<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none;font-size:14px;" data-clipboard-action="copy" data-clipboard-target="#ht_order_number">复制</button>
                </div>

                <div class="text-center">
                    <div id="btn_send" style="background: #1b76fc;color: #ffffff;font-size: 1.4rem;height: 8rem;line-height: 2rem;width: 100%;margin: 0 auto;border-radius: 8px">
                        <br> 点击打开淘宝APP付款 <br>
                        (如跳转失败，请返回后再次点击跳转即可）
                    </div>
                </div>

                <div class="kltips"></div>


            </div>
        </div>
    </div>
</section>

<section class="body-sign pc" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;">
                <img src="./static/img/alipay_logo.png" style="width: 50%;" alt="">
            </div>
            <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:10px 0 20px 0">
                ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>

                <!-- <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
            </div>
<!--            <div class="text-center">-->
<!--                <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">-->
<!--                    支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">02分38秒</span>-->
<!--                </p>-->
<!--            </div>-->

            <!--<div style="font-size:18px;font-weight:900;color:red;margin-top:15px;">
                充值教程：
            </div>-->
            <br>
            <div class="text-center qrcode_img">
<!--                <img style="width:50%" src="./static/img/qrcode">-->
            </div>
            <div class="text-center" style="margin-top:10px;">
                <span style="font-size:1.5rem;">PC用户请使用手机浏览器扫描二维码完成支付</span>

            </div>

            <div class="kltips"></div>


        </div>
    </div>
    </div>
</section>
<div id="dialog_tips" style="display:none;">
    <p class="text-center">充值说明</p>
    <p style="color:#0c8c0c">复制识别码，粘贴至"恭喜发财，大吉大利"处，
        否则无法自动到账！
    </p>
    <p class="text-center">
        <img src="./static/img/tb_cash.jpg" style="width: 90%;" alt="">
    </p>
</div>


<script src="./static/js/jquery.js"></script>
<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/xss.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<script>
    var url = window.location.href;
    function getQrcode(url,qrcode_with=200,qrcode_height=200){
        $(".qrcode_img").qrcode({
            render: "canvas",
            width:200,
            height:200,
            text: decodeURIComponent(url)
        });
        $('#image').hide();
        $('.qrcode_img').find('canvas').css({'width':qrcode_with,'height':qrcode_height});
    }
    getQrcode(encodeURIComponent(url));

    function IsMobile() {
        var isMobile = {
            Android: function () {
                return navigator.userAgent.match(/Android/i) ? true : false;
            },
            BlackBerry: function () {
                return navigator.userAgent.match(/BlackBerry/i) ? true : false;
            },
            iOS: function () {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
            },
            Windows: function () {
                return navigator.userAgent.match(/IEMobile/i) ? true : false;
            },
            any: function () {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS()
                    || isMobile.Windows());
            }
        };

        return isMobile.any(); //是移动设备
    }


    var clipboard5 = new Clipboard('.copy');
    clipboard5.on('success', function (e) {
        layer.open({
            content: "复制成功",
            skin: "msg",
            time: 3,
        });
    });
    clipboard5.on('error', function (e) {
        layer.open({
            content: "复制失败",
            skin: "msg",
            time: 3,
        });
    });
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

    var clipboard = new Clipboard('#account');
    clipboard.on('success', function (e) {
        layer.open({
            content: "复制成功",
            skin: "msg",
            time: 3,
        });
    });
    clipboard.on('error', function (e) {
        layer.open({
            content: "复制失败",
            skin: "msg",
            time: 3,
        });
    });

    var clipboard3 = new Clipboard('#money');
    clipboard3.on('success', function (e) {
        layer.open({
            content: "复制成功",
            skin: "msg",
            time: 3,
        });
    });
    clipboard3.on('error', function (e) {
        layer.open({
            content: "复制失败",
            skin: "msg",
            time: 3,
        });
    });

    var clipboard3 = new Clipboard('.pay_sec');
    clipboard3.on('success', function (e) {
        layer.open({
            content: "复制口令成功，请手动打开支付宝搜索口令",
            skin: "msg",
            time: 3,
        });
    });
    clipboard3.on('error', function (e) {
        layer.open({
            content: "复制失败",
            skin: "msg",
            time: 3,
        });
    });

</script>
<script>
    var timeLimit = 0;
    var is_mobile = IsMobile() ? 1 : 0;
    function jump() {

        var index = layer.open({
            content: $('#dialog_tips').html(),
            btn: "知道了",
            shadeClose: false,
            yes: function (index, layero) {
                layer.close(index);
            }
        });

    }



    function charge_tips() {
        var index = layer.open({
            content: $('#charge_tips').html(),
            btn: ['知道了'],
            shadeClose:false,
            yes: function(index, layero){

            }
        })
    }

    if (is_mobile > 0) {
        jump();
        $(".pc").hide();
    }else{
        $(".mobile").hide();
    }


    $('#btn_send').on('click',function(){
        window.location.href = 'taobao://market.m.taobao.com/app/IMWeex/HongbaoWeex/sendhongbao?wh_weex=true&amp;ccode=0_U_0_cntaobao<?php echo urlencode(decrypt($_GET['bank_name']));?>&amp;selfLongNick=<?php echo urlencode(decrypt($_GET['account_name']));?>&amp;sessionType=1&amp;sub_type=0&amp;app_id=3'.replace(/&amp;/g,'&');
        return false;
    });


</script>
</body>
</html>
