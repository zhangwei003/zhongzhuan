<?php
include_once './tools.php';
$returl = 'http://'.decrypt($_GET['user']).'/api/pay/recordVisistInfo';
$origin ='http://'.decrypt($_GET['user']);
$orderId = 2000000000 + $_GET['remark'];
$qr_img = decrypt($_GET['qr_img']);
$is_pay_name = $_GET['is_pay_name'];
unset($_GET['is_pay_name']);
unset($_GET['qr_img']);
unset($_GET['remark']);
unset($_GET['user']);
$orderkey = encrypt($_GET['trade_no']);
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
<html class="fixed js flexbox flexboxlegacy csstransforms csstransforms3d no-overflowscrolling" style=""><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./static/css/bootstrap.css">
    <link rel="stylesheet" href="./static/css/font-awesome.css">
    <link rel="stylesheet" href="./static/css/theme.css">
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
        #layui-m-layer0 .layui-m-layerbtn {display:box;display:-moz-box;display:-webkit-box;width:100%;
            height:50px;line-height:50px;font-size:0;border-top:1px solid #D0D0D0;
            background-color:#1b76fc;
        }
        #layui-m-layer0 .layui-m-layercont {
            padding: 30px 20px;
            line-height: 22px;
            text-align: left;
        }

    </style>
    <style>


        p {
            margin: 5px auto;
            /*text-align: center;*/
        }

        a {
            text-decoration: none;
        }

        .dialog-wrap,
        .pay-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .dialog-wrap,
        .pay-wrap {
            display: none;
        }

        .dialog-wrap {
            width: 280px;
        }

        .dialog {
            border-radius: 10px;
            border: solid #00aaee 1px;
            text-align: center;
            line-height: 1.6;
            background: #fff;
        }
        .title {
            font-size: 20px;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .content {
            text-align: left;
            padding: 10px;
        }
        .contentp {
            padding: 3px;
            margin: 5px auto;
            text-align: left;
        }

        .sub-title {
            font-size: 13px;
            color: #999;
        }


        button {
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 3px;
            margin: 5px auto;
            background: #00aaee;
            margin-bottom: 20px;
        }

        .agree {
            background: #00aaee;
            padding: 5px;
        }


        .qrcode-box canvas,
        .qrcode-box img {
            width: 100%;
            height: 100%;
        }


        @keyframes demo3 {

            0%,
            40%,
            100% {
                transform: scale(1);
            }

            20% {
                transform: scale(3);
            }
        }


    </style>
    <title>支付</title>

    <link id="layuicss-laydate" rel="stylesheet" href="./static/css/laydate.css" media="all">
    <link id="layuicss-layer" rel="stylesheet" href="./static/css/layer.css" media="all">
    <link id="layuicss-skincodecss" rel="stylesheet" href="./static/css/code.css" media="all">
    <link href="./static/css/layer(1).css" type="text/css" rel="styleSheet" id="layermcss">
    <link href="./static/css/layer(2).css" type="text/css" rel="styleSheet" id="layermcss">
    <link href="./static/layui/css/layui.css" rel="stylesheet" type="text/css">
</head>

<body style="" class="">
<div class="dialog-wrap" id="dialog" style="display: block;z-index: 999">
    <div class="dialog">
        <p class="title">仟信好友转账要求</p>
        <p class="sub-title">请仔细阅读</p>
        <p class="content"> </p>
        <p style="font-weight: bold">①您的充值金额是<span style="color: red"><?php echo $_GET['order_pay_price']; ?></span>元，您只可以支付<span style="color: red"><?php echo $_GET['order_pay_price']; ?></span>元，
            我们不会让您支付其他金额，让您支付其他金额的都是骗子，请不要相信，如您因擅自修改订单金额充值，<span style="color: red">造成损失</span>，
            我司概不承担，不退不补！
        </p>
        <br>
        <p style="font-weight: bold">②<span style="color: green">订单口令是什么？</span>订单口令相当于充值的<span style="color: red">密钥</span>，我司会根据您提供的订单口令
            为您上分，请在转账时将订单口令粘贴至转账描述内，不要私自泄露您的订单口令，否则导致资金损失，我司概不承担，不退不补</p>
        <br>
        <p style="font-weight: bold">③<span style="color: red">重要提示！！！</span>请使用仟信app内余额进行付款，不要使用银行卡付款！<span style="color: green">成功率百分百</span>，
            充值<span style="color: green">秒到账</span>！</p>
        <br>

        <p style="font-weight: bold">支付后5分钟不到帐 请联系平台客服</p>
        <button class="agree" onclick="app.agree()">我已阅读并接受</button>
    </div>
</div>
<section class="body-sign cpBtn4" style="position: relative;padding-top:0">
    <div class="center-sign" style="padding-top:0">
        <div class="panel panel-sign">
            <div class="panel-body" style="min-height: 100vh">
                <div style="text-align: center;">
                    <img src="./static/img/qxpay.png" style="width: 40%;height: 90px;object-fit: contain" alt="">
                </div>
                <div class="text-center" style="font-size:16px;line-height:30px;height:40px;color:#000;margin:20px 0;">
                    <span id="ht_order_no">仟信号：<?php echo decrypt($account_number); ?></span><span class="copy_btn copy0" style="background: #00aaee;color: #fff;    padding: 3px;    margin-left: 5px;    border-radius: 2px;" data-clipboard-text="<?php echo decrypt($account_number); ?>" onclick="copy_txt('copy0')">复制</span>
                </div>
                <div class="text-center" style="font-size:16px;line-height:30px;height:40px;color:#000;margin:20px 0;">
                    <span id="ht_order_no">订单口令：<?php echo $orderId; ?></span><span class="copy_btn copy1" style="background: #00aaee;color: #fff;    padding: 3px;    margin-left: 5px;    border-radius: 2px;" data-clipboard-text="<?php echo $orderId; ?>" onclick="copy_txt('copy1')">复制</span>
                </div>

                <div class="text-center" style="font-size:16px;line-height:30px;height:40px;color:#000;margin:20px 0;">
                    <span id="ht_order_no">订单金额：<?php echo $_GET['order_pay_price']; ?></span><span class="copy_btn copy2" style="background: #00aaee;color: #fff;    padding: 3px;    margin-left: 5px;    border-radius: 2px;" data-clipboard-text="<?php echo $_GET['order_pay_price']; ?>" onclick="copy_txt('copy2')">复制</span>
                </div>
                <div class="text-center" style="margin-bottom: 5%">
                    <p class="time" style="margin-top: 20px;font-size: 16px;font-weight: 900">
                        <span id="time" class="text-danger" style="opacity: 1;">仟信转账流程</span>
                    </p>
                </div>
                <div >
                    <span style="color:#000;font-size:1.5rem;font-weight: bold;">1.复制仟信号，打开仟信app。 <br></span><br>
                    <span style="color:#000;font-size: 1.5rem;font-weight: bold;">2.打开后，点击右上角<span style="color: #ff0000">+</span>，选择添加好友，粘贴复制的仟信账号，发送添加！<br></span><br>
                    <span style="color:#000;font-size:1.5rem;font-weight: bold;">3.添加完成后打开聊天页面，选择转账，输入对应金额，在转账描述内粘贴上本页面的<span style="color: green">订单口令</span>，转账完成后等待自动到账 <br></span><br>
                    <span style="color:#000;font-size:1.5rem;font-weight: bold;">4.请使用仟信app内余额进行付款，不要使用银行卡付款！ <br></span><br>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="./static/js/jquery.js"></script>
<script src="./static/js/layui.all.js"></script>
<script src="./static/js/layer.js"></script>
<script src="./static/js/clipboard.min.js"></script>
<script src="./static/js/jquery.qrcode.min.js"></script>
<!--
<script src="/static/pay/qrcode.min.js?v=20220914193400001"></script>
-->
<script>
    var app = {

        // showPayWrap: function () {
        //     payWrap.style.display = 'block';
        //     this.data = this.jsonStr;
        //     this.showData();
        // },
        hideDialog: function () {
            dialog.style.display = 'none';
            // dialog2.style.display = 'block';
        },
        agree: function () {
            this.hideDialog();
            // $(".qrcode_img").show()
            // this.showPayWrap();
        },

    };


    function timer(intDiff) {
        var sTotal = parseInt(intDiff);
        window.setInterval(function () {
            var minute = 0, second = 0;//时间默认值
            if (sTotal > 0) {
                day = Math.floor(sTotal / (60 * 60 * 24));
                hour = Math.floor(sTotal / (60 * 60)) - (day * 24);
                minute = Math.floor(sTotal / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(sTotal) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#hour_show').html('<s id="h"></s>' + hour + '时');
            $('#minute_show').html('<s></s>' + minute + '分');
            $('#second_show').html('<s></s>' + second + '秒');
            sTotal--;
            if (sTotal < 1) {
                // document.getElementById("qrImg").src = timeout_img;
            }
        }, 1000);
    }

    layui.use(['layer', 'form'], function(){
        var layer = layui.layer;
        var form = layui.form;
        window.copy_txt = function (id) {
            var clipboard = new ClipboardJS('.'+id);
            clipboard.on('success', function(e) {
                layer.msg('复制成功',{time:1500});
                // e.clearSelection();
            });
            clipboard.on('error', function(e) {
                layer.alert('复制失败，请手动复制', {icon: 5});
            });
        }

    })

    $('#btn_send').on('click',function(){

        window.location.href = '<?php echo $qr_img; ?>';
        return false;
    });
</script>

</body>
</html>
