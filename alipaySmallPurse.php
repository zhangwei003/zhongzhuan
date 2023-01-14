<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$orderId = 20000000 + $_GET['remark'];
$UPDATE_PAY_USER_NAME = 'http://'.decrypt($_GET['user']).'/api/pay/updateOrderPayUsername';
$is_pay_name = $_GET['is_pay_name'];
$gourl = decrypt($_GET['qr_img']);
unset($_GET['qr_img']);
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
<!-- saved from url=(0114)http://pay5.wuhengshop.com/c/api/pay?osn=2023010223011767780000861&t=1672671677&k=a2ca3d67f44983cbfbe851101c226267 -->
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
        #qrcode img {
            margin:0 auto;
        }
    </style>
    <title>支付</title>
    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="./static/css/layer.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/css/layer(1).css" type="text/css" rel="styleSheet" id="layermcss">
    <link href="./static/css/layer(2).css" type="text/css" rel="styleSheet" id="layermcss"></head>
<body style="">
<!-- start: page -->

<section class="body-sign" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;" "="">
                <img src="./static/img/alipay_logo.png" style="width: 50%;" alt="">
            </div>
            <div class="text-center" style="font-size:38px;line-height:40px;height:40px;color:#000;margin:10px 0 20px 0">
                ￥<span id="balance"><?php echo $_GET['order_pay_price']; ?></span>

                <!-- <button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
            </div>
            <!--            <div class="text-center">-->
            <!--                <p class="" style="margin-top: 20px;font-size: 16px;font-weight: 900">-->
            <!--                    支付倒计时：<span id="time" class="text-danger" style="opacity: 1;">04分39秒</span>-->
            <!--                </p>-->
            <!--            </div>-->
            <div class="text-center">
                <div id="btn_send" style="background: #1b76fc;color: #ffffff;font-size: 1.4rem;height: 4rem;line-height: 4rem;width: 90%;margin: 0 auto;border-radius: 4px">
                    点击复制并打开支付宝
                </div>
            </div>
            <br>
            <div class="text-center qrcode_img">
                <div id="qrcode" title="<?php echo $gourl; ?>">
                </div>



                <div class="text-center" style="font-weight: bold;font-size:18px;line-height:25px;height:25px;color:#ff0000;margin:25px 0;">
                    <p style="color:#0c8c0c">
                        支付宝扫二维码添加客服好友，发送下方“口令”给客服，<span style="color:#ff0000"> 不要直接转账，无法到账不退还！！！</span>
                    </p>


                    <!--<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none" data-clipboard-action="copy" data-clipboard-target="#balance">复制</button>-->
                </div>
                <br>
                <div class="text-center" style="font-weight: bold;font-size:16px;line-height:40px;height:40px;color:#ff0000;margin:10px 0;">
                    <span id="ht_order_no">口令：<span id="ht_order_number"><?php echo $orderId; ?></span></span>

                    &nbsp;&nbsp;<button type="button" class="btn btn-info btn-xs copy2" style="background-color:rgb(246,52,53) !important;border:none;font-size:14px;" data-clipboard-action="copy" data-clipboard-target="#ht_order_number">复制</button>
                </div>
                <div class="text-center">
                    <img style="width:98%" src="./static/img/alipay_pouch.jpg">
                </div>

                <div style="font-size:18px;font-weight:900;color:red;margin-top:15px;">
                    充值教程：
                </div>

                <div style="font-size:14px;color:red;margin-top:5px;margin-top:10px;font-weight: bold;">
                    1.打开支付宝，截屏扫码添加客服好友，复制上方“口令”发送给客服<br>
                    2.支付宝搜索小荷包，然后进入小荷包，转入当前订单相应金额，不要多付少付！<br>
                    3.邀请客服好友加入你的小荷包，等待客服将钱转出即可成功上分<br>
                    4.订单一次性有效，再次充值请重新发起订单！不要私自转账，以免造成损失！
                </div>

                <!--<div class="text-center">
                    <div id="btn_send" style="background: #1b76fc;color: #ffffff;font-size: 1.5rem;height: 5rem;line-height: 5rem;width: 90%;margin: 0 auto;border-radius: 8px">
                        启动支付宝APP支付
                    </div>
                </div>
                <br />-->




                <div class="kltips"></div>


            </div>
        </div>
    </div>
</section>
<div id="dialog_tips" >
    <span style="text-align: center;font-size: 1.8rem;font-weight: bold;color: #423f3f;">

   <p style="text-align: center;color:red;">  注意！注意！注意！</p>
        <br>
    <p>支付宝扫码后，先点<span style="color: red">【加好友】</span> </p>
    <p>然后按照客服的引导充值</p>
    <p><span style="color: red">不要</span>直接点【去转账】</p>
    <p><span style="color: red">不要</span>直接点【发红包】</p>
    <p>按要求充值，秒到账</p>
    </span>
</div>

<script src="./static/js/jquery.js"></script>
<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text:  "<?php echo $gourl; ?>".replace(/&amp;/g,'&'),
        width: 180,
        height: 180,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    })
    $('#btn_send').on('click',function(){
        $('.copy2').click();
        setTimeout(function(){
            var url = '<?php echo $gourl; ?>';
            window.location.href = url;
        },10)
    });

</script>
<script>

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

    jump();
    function jump() {
        var index = layer.open({
            content: $('#dialog_tips').html(),
            btn: "知道了",
            shadeClose: false,
            yes: function (index, layero) {
                //if (timeLimit == 5) {
                layer.close(index);
                $('#dialog_tips').hide();
                // }
            },
        })
    }
</script>


</body>
</html>
